const StoreMapGenerator = {
    //Listeners
    listeners: [],
    segmentedListeners: [],
    room_title: '',
    room_image: '',
    room_active: false,
    location_positions: [],
    position_selected: null,
    urlForm: '',
    search: '',
    map: [
        [null, null, null, null, null, null, null, null, null, null],
        [null, null, null, null, null, null, null, null, null, null],
        [null, null, null, null, null, null, null, null, null, null],
        [null, null, null, null, null, null, null, null, null, null],
        [null, null, null, null, null, null, null, null, null, null],
    ],
    initial_map: null,//is for edit purpuses
    initial_room_image: '',
    setInitialsPositions(location_positions, initial_map) {
        StoreMapGenerator.location_positions = location_positions;
        if (Array.isArray(location_positions) && location_positions.length) {
            StoreMapGenerator.position_selected = location_positions[0];
        }
        if (initial_map) {
            StoreMapGenerator.map = initial_map;
            // StoreMapGenerator.initial_map = StoreMapGenerator.arrayClone(initial_map);
        }
        window.StoreMapGenerator = StoreMapGenerator;
    },
    /**
     * Set a position in the map
     * @param position
     * @param rowToInsert
     * @param cellToInsert
     */
    setPositionToCell(position, rowToInsert, cellToInsert) {
        let map = StoreMapGenerator.get('map');

        if (Array.isArray(map)) {

            let row = !!(map[rowToInsert]) ? map[rowToInsert] : null;
            if (Array.isArray(row)) {

                if (map[rowToInsert][cellToInsert] !== undefined) {

                    map[rowToInsert][cellToInsert] = position;
                    StoreMapGenerator.set('map', map);
                }
            }
        }
    },
    /**
     *
     * @returns {number}
     */
    getPositionCount(type) {
        let count = 0;
        let map = StoreMapGenerator.get('map');
        if (Array.isArray(map)) {
            map.forEach((row) => {
                if (Array.isArray(row)) {
                    row.forEach((cell) => {
                        if (cell !== null) {
                            if (type) {
                                let cellType = cell.type;
                                if (cellType === type) {
                                    count++;
                                }
                            } else {
                                count++;
                            }
                        }
                    })
                }
            });
        }

        return count;
    },
    /**
     * Initial Map
     * @returns {[*]}
     */
    initialMap() {
        return [
            [null, null, null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null, null],
            [null, null, null, null, null, null, null, null, null, null],
        ];
    },
    /**
     * Returns a clean map with current rows and columns
     * @returns {Array}
     */
    cleanMap() {
        let map = StoreMapGenerator.get('map');
        let countRows = StoreMapGenerator.countRows();
        let countColumns = StoreMapGenerator.countColumns();

        let newMap = [];
        for (let row = 1; row <= countRows; row++) {
            let newRow = StoreMapGenerator.createArray(countColumns, null);
            newMap.unshift(newRow);
        }
        return newMap;
    },
    initialTitle() {
        return '';
    },
    initialRoomImage() {
        return '';
    },
    /**
     * Helper to generat new rows
     * @param size
     * @param defaultVal
     * @returns {Array}
     */
    createArray(size, defaultVal) {
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
     * Add row to top
     */
    addTopRow() {
        let columnsTotals = StoreMapGenerator.countColumns();
        let map = StoreMapGenerator.get('map');
        let newRow = StoreMapGenerator.createArray(columnsTotals, null);
        map.unshift(newRow);

        StoreMapGenerator.set('map', map);
    },
    /**
     * Remov row in top
     */
    removeTopRow() {
        let rowTotals = StoreMapGenerator.countRows();
        if (rowTotals > 1) {
            let map = StoreMapGenerator.get('map');
            map.shift();

            StoreMapGenerator.set('map', map);
        }
    },
    /**
     * Add row to bottom
     */
    addBottomRow() {
        let columnsTotals = StoreMapGenerator.countColumns();
        let map = StoreMapGenerator.get('map');
        let newRow = StoreMapGenerator.createArray(columnsTotals, null);
        map.push(newRow);

        StoreMapGenerator.set('map', map);
    },
    /**
     * Remove row in bottom
     */
    removeBottomRow() {
        let rowTotals = StoreMapGenerator.countRows();
        if (rowTotals > 1) {
            let map = StoreMapGenerator.get('map');
            map.pop();

            StoreMapGenerator.set('map', map);
        }
    },
    /**
     * Count number of rows
     * @returns {number}
     */
    countRows() {
        let map = StoreMapGenerator.get('map');
        return Array.isArray(map) ? map.length : 0;
    },
    /**
     * Count number of columns
     * @returns {number}
     */
    countColumns() {
        let firstRow = StoreMapGenerator.getRowByIndex(0);

        return Array.isArray(firstRow) ? firstRow.length : 0;
    },
    /**
     * Get Row By Index
     * @param index
     * @returns {*}
     */
    getRowByIndex(index) {
        let map = StoreMapGenerator.get('map');
        return map[index];
    },
    /**
     * Add Column to right
     * Add a cell to the end of every row
     */
    addColumnRight() {
        let map = StoreMapGenerator.get('map');
        if (Array.isArray(map)) {
            map.forEach((row) => {
                if (Array.isArray(row)) {
                    //Add cell to row in the end
                    row.push(null)
                }
            });
            StoreMapGenerator.set('map', map);
        }
    },
    /**
     * Remove Column to right
     * Remove a cell to the end of every row
     */
    removeColumnRight() {
        let map = StoreMapGenerator.get('map');
        if (Array.isArray(map)) {
            map.forEach((row) => {
                if (Array.isArray(row) && row.length > 1) {
                    //remove cell to row in the end
                    row.pop()
                }
            });
            StoreMapGenerator.set('map', map);
        }
    },
    /**
     * Add Column to left
     * Add a cell at the start of every row
     */
    addColumnLeft() {
        let map = StoreMapGenerator.get('map');
        if (Array.isArray(map)) {
            map.forEach((row) => {
                if (Array.isArray(row)) {
                    //Add cell to row in the start
                    row.unshift(null)
                }
            });
            StoreMapGenerator.set('map', map);
        }
    },
    /**
     * Add Column to left
     * Remove a cell at the start of every row
     */
    removeColumnLeft() {
        let map = StoreMapGenerator.get('map');
        if (Array.isArray(map)) {
            map.forEach((row) => {
                if (Array.isArray(row) && row.length > 1) {
                    //remove cell to row in the start
                    row.shift()
                }
            });
            StoreMapGenerator.set('map', map);
        }
    },
    /**
     * ResetMap
     */
    resetMap(cb) {
        // this.room_title = this.initialTitle();
        StoreMapGenerator.room_image = StoreMapGenerator.initialRoomImage();
        // StoreMapGenerator.map = StoreMapGenerator.initialMap();
        if (StoreMapGenerator.initial_map) {
            StoreMapGenerator.map = StoreMapGenerator.arrayClone(StoreMapGenerator.initial_map);
        } else {
            StoreMapGenerator.map = StoreMapGenerator.cleanMap();
        }

        StoreMapGenerator.search = '';
        StoreMapGenerator.TriggerChange(cb);
    },
    /**
     *
     * @param arr
     * @returns {*}
     */
    arrayClone(arr) {
        let i, copy;

        if (Array.isArray(arr)) {
            copy = arr.slice(0);
            for (i = 0; i < copy.length; i++) {
                copy[i] = StoreMapGenerator.arrayClone(copy[i]);
            }
            return copy;
        } else if (typeof arr === 'object') {
            return Object.assign({}, arr);
        } else {
            return arr;
        }
    },
    /**
     *
     * @param property
     * @param value
     * @param cb
     */
    set(property, value, cb) {
        this[property] = value ? value : null;
        this.TriggerChange(cb, property);
    },
    /**
     *
     * @param property
     * @returns {*}
     */
    get(property) {
        return this[property];
    },
    /**
     * Annade un listener
     * @param callback
     */
    addListener(callback) {
        this.listeners.push(callback);
    },
    /**
     *
     * @param segment
     * @param callback
     */
    addSegmentedListener(segment, callback) {
        if (segment && callback) {
            if (segment.length) {
                //array
                segment.forEach(function (singleSegment) {
                    StoreMapGenerator.subscribeToSegment(singleSegment, callback)
                })
            } else {
                //no array
                StoreMapGenerator.subscribeToSegment(segment, callback)
            }
        }
    },
    /**
     *
     * @param segment
     * @param callback
     */
    subscribeToSegment(segment, callback) {
        if (!this.segmentedListeners.hasOwnProperty(segment)) {
            this.segmentedListeners[segment] = [];
        }
        this.segmentedListeners[segment].push(callback);
    },
    /**
     * Lanza el eventos
     * @constructor
     */
    TriggerChange(cb, segment) {
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

export default StoreMapGenerator;
