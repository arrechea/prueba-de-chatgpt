import React from 'react'
import StoreReservation from "../../reservation/process/StoreReservation";
import UserCredits from "./UserCredits";

export default class UserCard extends React.Component {


    render() {
        let user = StoreReservation.get('user');

        if (!user) {
            return null;
        } else {
            let icon = user.icon ? user.icon : '';
            let lang = StoreReservation.get('lang');

            return (
                <div className="WidgetBUQ--UserCard">
                    <div className="WidgetBUQ--UserCard--info">
                        <div className="WidgetBUQ--UserCard--info--icon">
                            <div
                                className="WidgetBUQ--UserCard--info--icon--holder"
                                style={{
                                    background: icon ? `url(${icon})` : null
                                }}
                            />
                        </div>
                        <div className="WidgetBUQ--UserCard--info--hello">
                            {lang['widget.profile.hello'].replace('%s', user.first_name)}<br/>
                            {lang['widget.profile.welcome']}
                        </div>
                    </div>
                    <UserCredits
                        credits={StoreReservation.user_Credits}
                    />
                </div>
            )
        }
    }
}
