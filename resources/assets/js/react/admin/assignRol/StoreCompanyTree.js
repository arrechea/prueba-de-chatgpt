const Store = {
    listeners: [],
    tree: {},
    /**
     *
     * @param tree
     */
    set(tree, cb){
        this.tree = tree ? tree : {};
        this.TriggerChange(cb);
    },
    /**
     * Annade o lo quita objeto a seleccion
     * @param cantidad
     * @param seleccionados
     */
    addToSelection(cantidad, seleccionados){
        if (Object.keys(seleccionados).length) {
            for (let id in seleccionados) {
                if (seleccionados.hasOwnProperty(id)) {
                    let producto = seleccionados[id];
                    this.productos[id] = {
                        producto: producto,
                        cantidad: parseInt(cantidad)
                    };
                }
            }
            this.TriggerChange();
        }
    },
    /**
     * Limpia los productos
     */
    limpiar(){
        this.productos = {};
        this.TriggerChange();
    },
    /**
     * Elimina de la seleccion
     * @param id
     */
    removeFromSelection(id){
        delete this.productos[id];
        this.TriggerChange();
    },
    /**
     * Annade un listener
     * @param callback
     */
    addListener(callback){
        this.listeners.push(callback);
    },
    /**
     * Lanza el eventos
     * @constructor
     */
    TriggerChange(cb){
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
