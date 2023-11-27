import * as React from "react";
import InputError from "../helpers/InputError";
import LoginStepTemplate from "./LoginStepTemplate";
import StoreReservation from "../StoreReservation";
import LoginSystem from "../helpers/LoginSystem";

export default class ForgotPasswordStepTemplate extends React.Component {
    constructor(p) {
        super(p);
        this.state = {
            username: 'mario@rehilete.com.mx',//todo Eliminar
            username_error: '',
            recovery_error: '',
        };
        this.errorMessage = "Por favor, completa este campo";
    }

    _handleRecovery() {
        let username = this.state.username;
        if (!username) {
            return alert('Completa los campos del formulario');
        }
        let componente = this;

        LoginSystem.passwordRecovery(username, (err, data) => {
            if (err) {
                return componente.setState({
                    recovery_error: err,
                });
            } else {
                alert('Hemos enviado un correo electrónico a su bandeja. Siga las instrucciones del mismo.');
            }
        });
    }

    _handleChange(input, e) {
        let text = e.target.value;
        let newState = {
            [input]: text
        };
        if (text === '') {
            newState[`${input}_error`] = this.errorMessage;
        } else {
            newState[`${input}_error`] = "";
        }
        this.setState(newState);
    }

    _goToLogin() {
        let store = this.props.store;
        store.setStep(<LoginStepTemplate next={this.props.next} next_name={this.props.next_name}
                                         store={this.props.store}/>, 'LoginStepTemplate', true);
    }

    render() {
        let lang = StoreReservation.get('lang');
        return (
            <div className="WidgetBUQ--PasswordBlockContainer">
                <div>
                    <div className="WidgetBUQ--PasswordBlockHeader">
                        <h2>{lang['widget.PasswordBlock.title']}</h2>
                        <p>{lang['widget.PasswordBlock.subtitle']}</p>
                    </div>
                    <div className="WidgetBUQ--FormBlock">
                        <input
                            onChange={this._handleChange.bind(this, 'username')}
                            placeholder="Correo"
                            value={this.state.username}
                            type="email"
                        />
                        <InputError text={this.state.username_error}/>
                        <button type="submit"
                                onClick={this._handleRecovery.bind(this)}
                        >{lang['widget.LinkButton.confirm']}
                        </button>
                        <InputError text={this.state.recovery_error}/>
                    </div>
                    <div className="WidgetBUQ--RegisterBlock">
                        <button
                            className="WidgetBUQ--LinkButton"
                            onClick={this._goToLogin.bind(this, 'login')}
                        >
                            {lang['widget.loginBlock.link']}
                        </button>
                    </div>
                </div>
            </div>
        )
    }
}
const styles = ({
    container: {
        paddingBottom: 30,
        paddingHorizontal: 12,
        paddingTop: 25,
    },
    textSpecial: {
        fontSize: 30,
    },
    registro: {
        marginTop: 20,
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
        flex: 1,
    },
    registroText: {},
    registroTextBottom: {
        marginLeft: 10,
        paddingVertical: 20,
    },
    registroTextBottomText: {
        fontFamily: 'larsseit-bold'
    },
});
