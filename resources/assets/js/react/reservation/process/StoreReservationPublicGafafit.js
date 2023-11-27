import StoreReservation from "./StoreReservation";

const StoreReservationPublicGafafit = {
    /**
     * Inicializar store
     */
    init(){
        window.StoreReservationPublicGafafit = StoreReservationPublicGafafit;
    },
    /**
     *
     * @param property
     * @returns {*}
     */
    get(property){
        return StoreReservation.get(property);
    },
    /**
     * Annade un listener
     * @param callback
     */
    addListener(callback){
        StoreReservation.listeners.push(callback);
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
                    StoreReservation.subscribeToSegment(singleSegment, callback)
                })
            } else {
                //no array
                StoreReservation.subscribeToSegment(segment, callback)
            }
        }
    },
};

export default StoreReservationPublicGafafit;
