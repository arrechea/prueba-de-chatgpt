const StoreItemList = {
    listeners: [],
    segmentedListeners: [],
    items: [],
    categories: [],
    products: [],
    lang: [],
    csrf: null,
    category_url: null,
    category_new_url: null,
    category_delete_url: null,
    product_url: null,
    product_new_url: null,
    product_delete_url: null,
    product_list_url: null,
    selected_category: null,
    datatable: null,

    /**
     *
     * @param property
     * @returns {*}
     */
    get(property) {
        return this[property];
    },
    getLang(attribute) {
        let lang = StoreItemList.lang;
        return lang[attribute] ? lang[attribute] : '';
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
                    StoreItemList.subscribeToSegment(singleSegment, callback)
                })
            } else {
                //no array
                StoreItemList.subscribeToSegment(segment, callback)
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
    }
};

export default StoreItemList;
