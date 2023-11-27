const Store = {
    listeners: [],
    texts: [],
    url: null,
    csrf: null,
    lang: null,
    implement: null,
    allowedImplementations: [
        'services',
        'staff',
    ],
    /**
     *
     * @param texts
     * @param cb
     */
    set(texts, cb) {
        this.texts = texts ? texts : [];
        this.TriggerChange(cb);
    },
    /**
     * Annade o lo quita objeto a seleccion
     * @param text
     */
    addToSelection(text) {
        this.texts.push(text);
        this.TriggerChange();
    },
    /**
     * Limpia los productos
     */
    limpiar() {
        this.texts = [];
        this.TriggerChange();
    },
    /**
     * Elimina de la seleccion
     * @param id
     * @param cb
     */
    removeFromSelection(id, cb) {
        let newTexts = [];
        let texts = this.texts;
        texts.forEach(function (text) {
            if (text.id !== id) {
                newTexts.push(text);
            }
        });
        this.set(texts, cb)
    },
    /**
     * Annade un listener
     * @param callback
     */
    addListener(callback) {
        this.listeners.push(callback);
    },
    /**
     * Lanza el eventos
     * @constructor
     */
    TriggerChange(cb) {
        let listeners = this.listeners;
        if (listeners.length) {
            listeners.forEach(function (callback) {
                callback()
            })
        }
        if (cb) {
            cb();
        }
    }
};

export default Store;
