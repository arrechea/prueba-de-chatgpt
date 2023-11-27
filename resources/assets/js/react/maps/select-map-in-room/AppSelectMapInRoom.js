import React from 'react'
import StoreSelectMapInRoom from "./StoreSelectMapInRoom";

export default class AppSelectMapInRoom extends React.Component {
    constructor(p) {
        super(p);
        this.state = {
            details: StoreSelectMapInRoom.get('details'),
            capacity: StoreSelectMapInRoom.get('capacity'),
            maps_id: StoreSelectMapInRoom.get('maps_id'),
        };
        StoreSelectMapInRoom.addListener(this.ListenerStore.bind(this))
    }

    /**
     *
     * @constructor
     */
    ListenerStore() {
        this.setState({
            details: StoreSelectMapInRoom.get('details'),
            capacity: StoreSelectMapInRoom.get('capacity'),
            maps_id: StoreSelectMapInRoom.get('maps_id'),
        });
    }

    changeCapacity() {
        let newCapacity = this.refs.capacityField.value;
        StoreSelectMapInRoom.set('capacity', newCapacity);
    }

    printForm() {
        let component = this;
        let {details, capacity, maps_id} = component.state;
        let lang = StoreSelectMapInRoom.get('lang');

        if (details === 'quantity') {
            return (
                <div>
                    <div className="input-field col s8 m4">
                        <input type="number" id="capacity" className="input" name="capacity" value={capacity}
                               required={true}
                               onChange={component.changeCapacity.bind(component)} ref="capacityField"/>
                        <label htmlFor="capacity" className="active">{lang['Capacity']}<span className={'red-asterisk'}> *</span></label>
                    </div>
                </div>
            )
        } else {
            let lang = StoreSelectMapInRoom.get('lang');

            let {activeMaps} = StoreSelectMapInRoom;
            if (!maps_id && Array.isArray(activeMaps)) {
                if (activeMaps.length < 1) {
                    return null
                }
                maps_id = activeMaps[0].id;
            }

            let capacityMap = component.calculateMapCapacity(activeMaps, maps_id);

            return (
                <div>
                    <div className="">
                        <select className="select" name="maps_id" value={maps_id}
                                onChange={component.changeMapId.bind(component)} ref="map_selector">
                            {activeMaps.map((map) => {
                                return (
                                    <option value={map.id}
                                            key={`AppSelectMapInRoom--option--${map.id}`}>{map.name}</option>
                                )
                            })}
                        </select>
                        <div className="input-field col s8 m4">
                            <input type="number" id="capacity_fake" name="capacity_fake" value={capacityMap} disabled/>
                            <input type="hidden" id="capacity" name="capacity" value={capacityMap}/>
                            <label htmlFor="capacity" className="active">{lang['Capacity']}</label>
                        </div>
                    </div>
                </div>
            )
        }
    }

    /**
     *
     * @param maps
     * @param mapsToCalculate
     * @returns {number}
     */
    calculateMapCapacity(maps, mapsToCalculate) {
        let capacity = 0;
        if (Array.isArray(maps) && maps.length) {
            maps.forEach((map) => {
                if (parseInt(map.id) === parseInt(mapsToCalculate)) {
                    capacity = map.capacity;
                }
            })
        }

        return capacity;
    }

    changeMapId() {
        let newMapIp = this.refs.map_selector.value;
        StoreSelectMapInRoom.set('maps_id', newMapIp);
    }

    changeDetails() {
        let newDetail = this;
        StoreSelectMapInRoom.set('details', newDetail);
    }

    render() {
        let lang = StoreSelectMapInRoom.get('lang');
        let {details} = this.state;
        let {activeMaps} = StoreSelectMapInRoom;
        let mapQuantity = 0;
        let mWidth = 'm12';

        if (Array.isArray(activeMaps)) {
            mapQuantity = activeMaps.length;
            mWidth = 'm6';
        }

        return (
            <div className="AppSelectMapInRoom">
                <div className="asas--selectDetails">
                    <div className={`col s12 ${mWidth}`}>
                        <input type="radio" name="details" id="AppSelectMapInRoom--details--quantity"
                               className="with-gap" value="quantity" checked={details === 'quantity'}
                               onChange={this.changeDetails.bind('quantity')}/>
                        <label htmlFor="AppSelectMapInRoom--details--quantity">{lang['Quantity']}</label>
                    </div>
                    {mapQuantity > 0 ?
                        (
                            <div className="col s12 m6">
                                <input type="radio" name="details" id="AppSelectMapInRoom--details--map"
                                       className="with-gap"
                                       value="map" checked={details === 'map'}
                                       onChange={this.changeDetails.bind('map')}/>
                                <label htmlFor="AppSelectMapInRoom--details--map">{lang['Map']}</label>
                            </div>
                        ) : null
                    }
                </div>
                {this.printForm()}
            </div>
        )
    }
}
