/**
 * This component Has 4 states
 * 1 - none
 * 2 - title (only title)
 * 3 - resume (location, meeting, staff and date)
 * 4 - full
 */
import React from 'react'
import {gsap} from "gsap";
import StoreWidget from "../StoreWidget";
import IconText from "./IconText";
import LocationList from "../locations/LocationList";
export default class BackHolder extends React.Component {
    /**
     *
     */
    constructor() {
        super();
        this.DISPLAY_NONE = 'none';
        this.DISPLAY_TITLE = 'title';
        this.DISPLAY_RESUME = 'resume';
        this.DISPLAY_FULL = 'full';

        this.state = {
            display: StoreWidget.backholder ? StoreWidget.backholder : this.DISPLAY_NONE,
        };
        StoreWidget.subscribeToSegment('backholder', this.backHolderListener.bind(this));
    }

    backHolderListener() {
        this.setState({
            display: StoreWidget.backholder,
        })
    }

    componentWillUnmount() {
        //Unsubscribe
        StoreWidget.segmentedListeners.backholder = [];
    }

    componentDidUpdate(prevProps, prevState) {
        let antes = prevState.display;
        let ahora = this.state.display;

        if (antes !== ahora) {
            let component = document.querySelector('.WidgetBUQ--BackHolder');
            let alto = 0;
            switch (ahora) {
                case  this.DISPLAY_TITLE:
                    alto = 128;
                    break;
                case  this.DISPLAY_RESUME:
                    alto = 200;
                    break;
            }
            gsap.to(
                component,
                1,
                {
                    height: alto,
                }
            );
        }
    }

    /**
     *
     * @private
     */
    _toogleTitleResume() {
        let ahora = this.state.display;
        if (ahora === this.DISPLAY_TITLE) {
            this.setState({
                display: this.DISPLAY_RESUME,
            })
        } else if (ahora === this.DISPLAY_RESUME) {
            this.setState({
                display: this.DISPLAY_TITLE,
            })
        }
    }

    _goOut() {
        this.setState({
            display: this.DISPLAY_NONE
        }, () => {
            StoreWidget.goToStep(<LocationList/>)
        });
    }

    render() {
        let lang = StoreWidget.lang;
        let location = StoreWidget.current_location;

        return (
            <div
                className="WidgetBUQ--BackHolder"
            >
               <div
                  className="WidgetBUQ--BackHolder--title"
                  onClick={this._toogleTitleResume.bind(this)}
               >
                  <h3>{lang['widget.backholder.title']}</h3>
                  <p>{lang['widget.backholder.subtitle']}</p>
                  <IconText
                     text=""
                     icon={<i className="far fa-chevron-down"></i>}
                  />
               </div>
                  <div
                     className="WidgetBUQ--BackHolder--Resume"
                  >
                    {location ? (
                        <div className="WidgetBUQ--BackHolder--ResumeItem">
                           <div className="WidgetBUQ--BackHolder--ResumeIcon">
                              <IconText
                                 text=""
                                 icon={<i className="fal fa-map-marker-alt"></i>}
                              />
                           </div>
                           <div className="WidgetBUQ--BackHolder--ResumeTitle">
                              {lang['widget.locationList.venue']}
                           </div>
                           <div className="WidgetBUQ--BackHolder--ResumeLocation">
                           {location.name}
                           </div>
                           <div className="WidgetBUQ--BackHolder--ResumeEdit">
                              <span
                                 onClick={this._goOut.bind(this, <LocationList/>)}
                              >
                                 <IconText 
                                    text=""
                                    icon={<i className="fal fa-edit"></i>}
                                 />
                              </span>
                           </div>
                        </div>
                    ) : null}
                </div>
            </div>
        )
    }
}
