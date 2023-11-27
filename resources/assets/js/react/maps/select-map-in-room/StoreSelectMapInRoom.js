const StoreSelectMapInRoom = {
    //Listeners
    listeners: [],
    segmentedListeners: [],
    lang: null,
    details: null,
    capacity: null,
    activeMaps: [],
    maps_id: null,
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
                    StoreSelectMapInRoom.subscribeToSegment(singleSegment, callback)
                })
            } else {
                //no array
                StoreSelectMapInRoom.subscribeToSegment(segment, callback)
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

export default StoreSelectMapInRoom;
