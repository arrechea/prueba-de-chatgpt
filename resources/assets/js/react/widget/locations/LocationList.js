import React from 'react'
import StoreWidget from "../StoreWidget";
import UpperBlock from "../common/UpperBlock";
import Card from "../common/Card";
import IconText from "../common/IconText";
import LocationSingle from "./LocationSingle";

export default class LocationList extends React.Component {
   componentDidMount() {
      StoreWidget.set('backholder', 'none');
   }

   /**
    *
    * @param location
    * @private
    */
   static _goToLocation(location) {
      if (location) {
         StoreWidget.goToStep(<LocationSingle location={location}/>);
      }
   }

    render() {
        let lang = StoreWidget.lang;
        let images = StoreWidget.images;

        let locations = StoreWidget.locations;

        return (
            <div
                className="WidgetBUQ--LocationList"
            >
                <UpperBlock
                    icon={<i className="far fa-map-marker-alt"></i>}
                >
                    {lang['widget.locationList']}
                </UpperBlock>
                <div className="WidgetBUQ--list">
                    {locations.map((location) => {
                        return (
                            <Card
                                onClick={LocationList._goToLocation.bind(null, location)}
                                key={ `WidgetBUQ--LocationList--${location.id}`}
                                title={location.name}
                                subtitle={location.city}
                                right={(
                                    <div>
                                        <IconText
                                            icon={<i className="fal fa-map-marker-alt"></i>}
                                            text={(
                                                <div>
                                                    {location.street} {location.number},<br/>
                                                    {location.suburb}, {location.city},<br/>
                                                    {location.postcode}
                                                </div>
                                            )}
                                        />
                                        <IconText
                                            icon={<i className="fal fa-phone-alt"></i>}
                                            text={location.phone}
                                        />
                                    </div>
                                )}
                            />
                        )
                    })}
                </div>
            </div>
        )
    }
}
