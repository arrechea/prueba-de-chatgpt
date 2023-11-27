import React from 'react'
import StoreWidget from "../StoreWidget";
import LocationList from "../locations/LocationList";
import Button from "../common/Button";
import StepHolder from "../common/StepHolder";
import IconText from "../common/IconText";
import BuqIcon from "../ui/icons/BuqIcon";
import Profile from "../profile/Profile";
export default class LowerBar extends React.Component {

    static _toogleWidget() {
        if (!StoreWidget.step) {
            LowerBar._goToLocation();
        } else {
            StepHolder._close();
        }
    }

    static _goToLocation() {
        StoreWidget.goToStep(<LocationList/>);
    }

    static _goToProfile() {
        StoreWidget.goToStep(<Profile/>);
    }

    render() {
        let lang = StoreWidget.lang;
        let images = StoreWidget.images;

        return (
            <div
                className="WidgetBUQ--LowerBar"
                style={{
                    backgroundColor: StoreWidget.color,
                }}
            >
                <div className="WidgetBUQ--LowerBar--left">
                    {StoreWidget.step ? (
                        <div>
                           <button
                              className="WidgetBUQ--LowerBar--icon"
                              onClick={LowerBar._goToProfile}
                           >
                              
                              <IconText
                                 text={null}
                                 icon={<i className="fal fa-user"></i>}
                              />
                           </button>
                           <button
                              className="WidgetBUQ--LowerBar--icon"
                              onClick={LowerBar._goToLocation}
                           >
                              <IconText
                                 text={null}
                                 icon={<i className="fal fa-calendar-alt"></i>}
                              />
                           </button>
                           <button
                              className="WidgetBUQ--LowerBar--icon"
                              // onClick={LowerBar._goToLocation}
                           >
                              <IconText
                                 text={null}
                                 icon={<i className="fal fa-shopping-cart"></i>}
                              />
                           </button>
                        </div>
                    ) :
                        <p className="WidgetBUQ--LowerBar--welcome">{lang['widget.welcome']}</p>
                    }
                </div>
                {
                    !StoreWidget.step ?
                        (
                            <div className="WidgetBUQ--LowerBar--center">
                                <Button
                                       text={StoreWidget.step ? lang['widget.close'] : lang['widget.reservate']}
                                       onClick={LowerBar._toogleWidget}
                                 />
                            </div>
                        ) : null
                }
                <div className="WidgetBUQ--LowerBar--right">
                  <BuqIcon />
                </div>
            </div>
        )
    }
}
