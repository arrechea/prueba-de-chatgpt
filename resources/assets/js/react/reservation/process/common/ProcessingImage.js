import React from 'react'
import StoreReservation from "../StoreReservation";
export default class ProcessingImage extends React.Component {
    render() {
        let images = StoreReservation.get('images');

        return (
            <div className="CreateReservationFancy--processing">
                <div className="CreateReservationFancy--processing--inner"/>
            </div>
        )
    }
}
