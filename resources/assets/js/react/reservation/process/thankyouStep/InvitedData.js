import React from 'react'
import StoreReservation from "../StoreReservation";
import FunctionHelper from "../../../../helpers/FunctionHelper";

export default class InvitedData extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            name: null,
            email: null,
            active: false
        }
    }

    static defaultProps() {
        return {
            index: 1,
            lang: {},
        }
    }

    componentDidMount() {
        // this.eliminarInfo();
    }

    componentWillUnmount() {
        // this.eliminarInfo();
    }

    eliminarInfo() {
        let {index} = this.props;
        let invitedData = StoreReservation.invited_data;
        delete invitedData[index];
        StoreReservation.set('invited_data', invitedData)
    }

    changeInfo(refName) {
        let {index} = this.props;

        let invitedData = StoreReservation.invited_data;
        if (!invitedData[index]) {
            invitedData[index] = {};
        }
        invitedData[index][refName] = this.refs[refName].value;
        let component = this;
        StoreReservation.set('invited_data', invitedData, () => {
            component.forceUpdate();
        });

        this.setState({
            name: this.refs['name'].value,
            email: this.refs['email'].value,
        })
    }

    getClassName(className) {
        if (!className) {
            className = 'Invited--input';
        }

        let {index} = this.props;
        let invitedData = StoreReservation.invited_data;

        if (this.isGeustInfoRequired()) {
            if (
                !invitedData[index]
                ||
                !invitedData[index]['name']
                ||
                invitedData[index]['name'] === ''
            ) {
                className += ' Invited--input__error';
            }
        }
        return className;
    }

    getClassEmail(className) {
        if (!className) {
            className = 'Invited--input';
        }

        let {index} = this.props;
        let invitedData = StoreReservation.invited_data;

        if (this.isGeustInfoRequired()) {
            if (
                !invitedData[index]
                ||
                !invitedData[index]['email']
                ||
                invitedData[index]['email'] === ''
                ||
                !this.validateEmail(this.refs.email.value)
            ) {
                className += ' Invited--input__error';
            }
        }
        return className;
    }

    validateEmail(email) {
        let helper = new FunctionHelper();
        return helper.validateEmail(email);
    }

    handleAccordeon(e) {
        e.preventDefault;
        const {active} = this.state;

        this.setState({
            active: !active,
        })
    }

    isGeustInfoRequired() {
        return StoreReservation.isGeustInfoRequired();
    }

        getInvitedEmailClass() {
        let {name, email} = this.state;
        if (this.isGeustInfoRequired()) {
            return !name || !email ? 'required' : '';
        }

        return '';
    }

    getInvitedWarning() {
        let {name} = this.state;
        let {lang} = this.props;

        if (name) {
            return name;
        }

        return this.isGeustInfoRequired() ? lang['guest.warning'] : lang['guest.optional'];
    }

    /**
     *
     * @returns {XML}
     */
    render() {
        let {
            lang, position
        } = this.props;
        const {active} = this.state;

        return (
            <div className={"Invited--email " + this.getInvitedEmailClass()}>
                <div className={"Invited--email__head"} onClick={this.handleAccordeon.bind(this)}>
                    <div className="this-invitedName">
                        {this.getInvitedWarning()}
                    </div>
                    <div className="this-invitedPosition">
                        {position}
                    </div>
                </div>
                <div className={"Invited--email__body" + (active ? ' is-active' : '')}>
                    <div>
                        <span
                            className={this.getClassName("Invited--label")}
                        >
                            {lang['confirm.invited_name']} *
                        </span>
                        <input
                            className={this.getClassName()}
                            type="text"
                            size="50"
                            ref="name"
                            onChange={this.changeInfo.bind(this, 'name')}
                        />
                    </div>
                    <div>
                        <span
                            className={this.getClassEmail("Invited--label")}
                        >
                            {lang['confirm.invited_email']} *
                        </span>
                        <input
                            className={this.getClassEmail()}
                            type="email"
                            size="50"
                            ref="email"
                            onChange={this.changeInfo.bind(this, 'email')}
                        />
                    </div>
                </div>
            </div>
        )
    }
}
