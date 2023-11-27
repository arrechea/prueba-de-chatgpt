import React from 'react'
import ReactDOM from 'react-dom'
import AppWidget from "./AppWidget";
import StoreWidget from "./StoreWidget";
import StoreReservation from "../reservation/process/StoreReservation";

let uuid = window.WidgetBUQUid;
if (uuid) {
    let contenedor = document.getElementById(`WidgetBUQ--${uuid}`);
    contenedor.querySelectorAll('div').forEach((child) => {
        let clase = child.getAttribute('class');
        let name = clase.split('WidgetBUQ--')[1];
        try {
            let text = child.textContent;
            StoreWidget[name] = StoreWidget.IsJsonString(text) ? JSON.parse(text) : text;
        } catch (e) {
            console.error(`${name}:\n`, e)
        }
    });

    //todo Set StoreReservation
    StoreReservation.location = StoreWidget.current_location = StoreWidget.locations[0];
    StoreWidget.current_brand = StoreWidget.current_location.brand;


    ReactDOM.render(<AppWidget/>, contenedor);
}
