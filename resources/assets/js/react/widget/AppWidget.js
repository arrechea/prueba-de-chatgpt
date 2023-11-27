import React from 'react'
import LowerBar from "./lower_bar/LowerBar";
import StoreWidget from "./StoreWidget";
import StepHolder from "./common/StepHolder";
import LocationSingle from "./locations/LocationSingle";
import ReservationProcess from "./reservation/ReservationProcess";
import Profile from "./profile/Profile";
import StoreReservation from "../reservation/process/StoreReservation";

export default class AppWidget extends React.Component {
    constructor() {
        super();
        this.state = {
            started: false,
            step: null,
        };
        StoreWidget.addSegmentedListener('step', this.ListenerStore.bind(this))
    }

    ListenerStore() {
        this.setState({
            step: StoreWidget.step
        });
    }

    componentDidMount() {
        let component = this;
        //Init Systems and Stores
        component._init(() => {
            //Init Widget
            component.setState({
                started: true
            }, () => {
                if (!!StoreWidget.meetings_id) {
                    StoreWidget.goToStep((
                        <ReservationProcess
                            brand={StoreWidget.current_brand.slug}
                            location={StoreWidget.current_location.slug}
                            meeting={{
                                id: StoreWidget.meetings_id,
                            }}
                        />
                    ), () => {
                        StoreWidget.set('backholder', 'title');
                    })
                } else {
                    //tests
                    StoreWidget.goToStep(<Profile/>);
                }
            });
        });
    }

    _init(callback) {
        global.GafaFitSDK.GetMe(() => {
            global.GafaFitSDK.GetCreateReservationForm(
                StoreWidget.current_brand.slug,
                StoreWidget.current_location.slug,
                null,
                null,
                {
                    forcejson: 'on',
                },
                (err, response) => {
                    if (!!response) {
                        StoreReservation.loguearConInfo(response, true, callback)
                    } else {
                        callback();
                    }
                }
            );
        });
    }

    render() {
        let {
            step,
            started
        } = this.state;
        if (!started) {
            return null;
        }
        return (
            <div className="WidgetBUQ">
                <StepHolder step={step}/>
                <LowerBar/>
            </div>
        )
    }
}
