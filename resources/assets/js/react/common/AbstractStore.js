export default {
    //Listeners
    listeners: [],
    segmentedListeners: [],

    /**
     * @return {boolean}
     */
    IsJsonString(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
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
     * @param defaultOptions
     * @returns {*}
     */
    get(property, defaultOptions = null) {
        return !!this[property] ? this[property] : defaultOptions;
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
            if (Array.isArray(segment)) {
                //array
                segment.forEach(function (singleSegment) {
                    this.subscribeToSegment(singleSegment, callback)
                })
            } else {
                //no array
                this.subscribeToSegment(segment, callback)
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

        if (segmentedListeners) {
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
    },
};
