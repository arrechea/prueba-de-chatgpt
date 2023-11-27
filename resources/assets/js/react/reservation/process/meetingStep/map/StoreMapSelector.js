import StoreReservation from "../../StoreReservation";
const StoreMapSelector = {
    //Listeners
    listeners: [],
    segmentedListeners: [],
    map_objectsSelected: [],
    map_positionList: {},
    map: null,
    mapLimit: 1,//todo en multireserva este valor se dinamizara
    reservations: [],
    init(map, reservations, cb){
        //location
        let location = StoreReservation.get('location');
        if (!!location.brand && !!location.brand.simultaneous_reservations) {
            StoreMapSelector.mapLimit = location.brand.simultaneous_reservations
        }

        //reservations
        StoreMapSelector.reservations = reservations;
        let newMap = StoreMapSelector.parseMap(map);
        StoreMapSelector.map_positionList = newMap.map_positionList;
        StoreMapSelector.set('map', newMap.map, cb);
    },
    /**
     * createEmptyRow
     * @param size
     * @param defaultVal
     */
    createEmptyRow(size, defaultVal) {
        let arr = new Array(size);
        if (arguments.length === 2) {
            // optional default value
            for (let i = 0; i < size; ++i) {
                arr[i] = defaultVal;
            }
        }
        return arr;
    },
    /**
     *
     * @param map
     * @returns {{map: Array, map_positionList: {}}}
     */
    parseMap(map) {
        let mapParsed = [];
        let reservations = StoreMapSelector.get('reservations');
        let map_objectsSelected = StoreMapSelector.get('map_objectsSelected');
        let map_positionList = {};
        let {rows, columns, objects} = map;

        //Generate Map
        for (let columnsIndex = 1; columnsIndex <= rows; columnsIndex++) {
            let newRow = StoreMapSelector.createEmptyRow(columns, null);
            mapParsed.push(newRow);
        }
        //InsertObjects
        if (Array.isArray(objects) && objects.length) {
            objects.forEach((object) => {
                let status = 'enabled';
                let positionId = object.positions.id;
                if (!map_positionList.hasOwnProperty(positionId)) {
                    //Insert in positionList
                    map_positionList[positionId] = object.positions;
                }
                mapParsed = StoreMapSelector.setInitPositionToCellInSpecificMap(mapParsed, object, status)
            })
        }
        //Insert Reservations
        if (Array.isArray(reservations) && reservations.length) {
            reservations.forEach((reservation) => {
                let {object} = reservation;
                let status = 'disabled';
                // console.log(positions,position_column,position_row);
                mapParsed = StoreMapSelector.setInitPositionToCellInSpecificMap(mapParsed, object, status)
            })
        }
        //Object selecteds
        if (Array.isArray(map_objectsSelected) && map_objectsSelected.length > 0) {
            map_objectsSelected.forEach((object) => {
                let status = 'selected';
                // console.log(positions,position_column,position_row);
                mapParsed = StoreMapSelector.setInitPositionToCellInSpecificMap(mapParsed, object, status)
            })
        }
        return {
            map: mapParsed,
            map_positionList: map_positionList
        };
    },
    /**
     * Set an Space in the current Map
     * @param mapParsed
     * @param object
     * @param status
     * @returns {*}
     */
    setInitPositionToCellInSpecificMap(mapParsed, object, status){
        let map = mapParsed;
        let rowToInsert = object.position_row;
        let cellToInsert = object.position_column;

        if (Array.isArray(map)) {

            let row = !!(map[rowToInsert]) ? map[rowToInsert] : null;
            if (Array.isArray(row)) {

                if (map[rowToInsert][cellToInsert] !== undefined) {

                    map[rowToInsert][cellToInsert] = {
                        status: status,
                        object: object
                    };
                }
            }
        }

        return map;
    },
    /**
     *
     * @param row
     * @param column
     */
    clickInPosition(row, column){
        let cell = StoreMapSelector.getCellByCoordinates(row, column);

        if (StoreMapSelector.cellIsClickable(cell)) {
            let status = StoreMapSelector.getCellStatus(cell);
            let newStatus = null;

            switch (status) {
                case 'selected':
                    //Pass to enabled
                    newStatus = 'enabled';
                    break;
                case 'enabled':
                    //Pass to selected
                    newStatus = 'selected';
                    break;
            }
            if (newStatus) {
                let newMap = StoreMapSelector.get('map');

                if (newStatus === 'enabled') {
                    //clear position in selector
                    newMap = StoreMapSelector.cleanSelectedPosition(newMap, cell);
                } else if (newStatus === 'selected') {
                    //Check map limit and update
                    newMap = StoreMapSelector.addSelectedPosition(newMap, cell);
                }

                //update this map
                StoreMapSelector.set('map', newMap, () => {
                    //update StoreReservation.map_objectsSelected
                    StoreReservation.set('map_objectsSelected', StoreMapSelector.map_objectsSelected);
                });
            }
        }
    },
    /**
     *
     * @param newMap
     * @param cell
     * @returns {*}
     */
    cleanSelectedPosition(newMap, cell){
        let object = StoreMapSelector.getCellObject(cell);
        newMap = StoreMapSelector.deleteObjectSelectedWithoutUpdateMainStore(newMap, object);
        return newMap;
    },
    addSelectedPosition(newMap, cell){
        let object = StoreMapSelector.getCellObject(cell);

        //Enable element
        newMap = StoreMapSelector.setInitPositionToCellInSpecificMap(newMap, object, 'selected');

        //calculate selected elements
        let mapLimit = StoreMapSelector.get('mapLimit');
        let objectsSelected = StoreMapSelector.map_objectsSelected;

        if (mapLimit <= objectsSelected.length && objectsSelected.length > 0) {
            //Clean Selected object
            newMap = StoreMapSelector.deleteObjectSelectedWithoutUpdateMainStore(newMap, StoreMapSelector.map_objectsSelected[0]);
        }

        //annadir nuevo valor a StoreMapSelector.map_objectsSelected al final
        StoreMapSelector.map_objectsSelected.push(object);

        return newMap;
    },
    deleteObjectSelectedWithoutUpdateMainStore(newMap, object){
        let objectClone = Object.assign({}, object);

        let map_objectsSelected = StoreMapSelector.map_objectsSelected;
        let indexToFilter = null;
        let objectToClean = map_objectsSelected.filter((selected, index) => {
            if (selected && object && selected.id === object.id) {
                indexToFilter = index;
            }
        });
        if (indexToFilter !== null) {
            StoreMapSelector.map_objectsSelected.splice(indexToFilter, 1);
        }

        //Clean Select element from map
        newMap = StoreMapSelector.setInitPositionToCellInSpecificMap(newMap, objectClone, 'enabled');

        return newMap;
    },
    cellIsClickable(cell){
        return (
            StoreMapSelector.cellIsPublic(cell)
            &&
            (
                StoreMapSelector.cellIsSelected(cell) || StoreMapSelector.cellIsEnabled(cell)
            )
        );
    },
    cellIsEnabled(cell){
        let status = StoreMapSelector.getCellStatus(cell);
        return status === 'enabled';
    },
    cellIsDisabled(cell){
        let status = StoreMapSelector.getCellStatus(cell);
        return status === 'disabled';
    },
    cellIsSelected(cell){
        let status = StoreMapSelector.getCellStatus(cell);
        return status === 'selected';
    },
    getCellStatus(cell){
        return cell.status;
    },
    getCellPosition(cell){
        let object = StoreMapSelector.getCellObject(cell);
        return object.positions;
    },
    getCellObject(cell){
        return cell.object;
    },
    cellIsPublic(cell) {
        let position = StoreMapSelector.getCellPosition(cell);
        return position.type === 'public'
    },
    cellIsPrivate(cell) {
        let position = StoreMapSelector.getCellPosition(cell);
        return position.type === 'private'
    },
    /**
     *
     * @param row
     * @param column
     * @returns {*}
     */
    getCellByCoordinates(row, column){
        let mapRow = StoreMapSelector.getRowByIndex(row);
        if (Array.isArray(mapRow)) {
            return mapRow[column]
        }
    },
    /**
     * Get Row By Index
     * @param index
     * @returns {*}
     */
    getRowByIndex(index){
        let map = StoreMapSelector.get('map');
        return map[index];
    },
    /**
     *
     * @param property
     * @param value
     * @param cb
     */
    set(property, value, cb){
        this[property] = value ? value : null;
        this.TriggerChange(cb, property);
    },
    /**
     *
     * @param property
     * @returns {*}
     */
    get(property){
        return this[property];
    },
    /**
     * Annade un listener
     * @param callback
     */
    addListener(callback){
        this.listeners.push(callback);
    },
    /**
     *
     * @param segment
     * @param callback
     */
    addSegmentedListener(segment, callback){
        if (segment && callback) {
            if (segment.length) {
                //array
                segment.forEach(function (singleSegment) {
                    StoreMapSelector.subscribeToSegment(singleSegment, callback)
                })
            } else {
                //no array
                StoreMapSelector.subscribeToSegment(segment, callback)
            }
        }
    },
    /**
     *
     * @param segment
     * @param callback
     */
    subscribeToSegment(segment, callback){
        if (!this.segmentedListeners.hasOwnProperty(segment)) {
            this.segmentedListeners[segment] = [];
        }
        this.segmentedListeners[segment].push(callback);
    },
    /**
     * Lanza el eventos
     * @constructor
     */
    TriggerChange(cb, segment){
        //Listeners
        let listeners = this.listeners;
        if (listeners.length) {
            listeners.forEach(function (callback) {
                callback()
            })
        }
        //SegmentedListeners
        let segmentedListeners = this.segmentedListeners;

        if (segmentedListeners && segment) {
            if (segmentedListeners.hasOwnProperty(segment)) {
                segmentedListeners[segment].forEach(function (callback) {
                    callback()
                })
            }
        }
        //Callback
        if (cb) {
            cb();
        }
    }
};

export default StoreMapSelector;
