const Store = {
    listeners: [],
    model: null,
    catalog: null,
    company: null,
    brand: null,
    fields_url: '',
    values_url: '',
    loading: {
        values: true,
        structure: true
    },
    default_value: {
        id: 0,
        model_id: 0,
        value: null,
        catalogs_groups_id: 0,
        catalogs_fields_id: 0,
        catalogs_groups_index: 0,
        catalogs_fields_index: 0,
        catalogs_groups_slug: '',
        catalogs_fields_slug: '',
    },

    setLoading(key,val,cb){
        this.loading[key]=val;
        if(cb){
            cb()
        }
    }
};

export default Store;
