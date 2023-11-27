import React from 'react'
import StoreReservation from "../StoreReservation";
import NextStepUserCreditsTemplate from "../userCreditsStep/NextStepUserCreditsTemplate";
export default class UserCreditsStepTemplate extends React.Component {
    /**
     *
     * @returns {*}
     */
    printTable() {
        let lang = StoreReservation.get('lang');
        let userCredits = StoreReservation.get('user_Credits');
        if (!userCredits || !userCredits.length) {
            return lang['noCredits'];
        }
        return (
            <table width="100%" cellPadding={2} className="UserCreditsStep--table">
                <thead>
                <tr>
                    <th colSpan={3}>{lang['myCredits']}</th>
                </tr>
                </thead>
                <tbody>
                {userCredits.map(function (credit) {
                    return (
                        <tr key={`UserCreditsStep--${credit.credits_id}`}>
                            <td>{credit.credit.picture ?
                                <img src={credit.credit.picture} alt="" height="50"/> : ''}</td>
                            <td>{credit.credit.name}</td>
                            <td>{credit.total} {lang['credits']}</td>
                        </tr>
                    )
                })}
                </tbody>
            </table>
        )
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        let lang = StoreReservation.get('lang');

        return (
            <div className="UserCreditsStep">
                {this.printTable()}
                <div className="AppReservation--steps">
                    <NextStepUserCreditsTemplate/>
                </div>
            </div>
        )
    }
}
