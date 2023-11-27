import * as React from "react";
import InputError from "../helpers/InputError";
import StoreReservation from "../StoreReservation";
import LoginStepTemplate from "./LoginStepTemplate";
import LoginSystem from "../helpers/LoginSystem";

export default class RegisterStepTemplate extends React.Component {
    constructor(props) {
        super(props);
        let user = StoreReservation.get('user');
        if (user && !!props.next) {
            store.setStep(props.next, props.next_name, true);
            return;
        }
        this.state = {
            first_name: 'Mario',//todo Eliminar
            first_name_error: '',
            last_name: 'Sanzol',//todo Eliminar
            last_name_error: '',
            username: 'mario' + (Math.random() * 500) + '@com.com',//todo Eliminar
            username_error: '',
            password: 'Wisquimas86',//todo Eliminar
            password_error: '',
            password_confirmation: 'Wisquimas86',//todo Eliminar
            password_confirmation_error: '',
            checked: true,//todo Eliminar pasando a false
            checked_error: '',
            registro_error: '',
        };
        this.errorMessage = "Por favor, completa este campo";
        this.politicasErrorMessage = 'Debes aceptar los términos para continuar';
        this.passwordError = 'Las contraseñas no coinciden';
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

    _formIsValid() {
        let {
            last_name,
            first_name,
            username,
            password,
            password_confirmation,
            checked
        } = this.state;
        let errors = {};
        let hasErrors = false;
        let errorText = this.errorMessage;
        if (!last_name) {
            hasErrors = true;
            errors.last_name_error = errorText;
        }
        if (!first_name) {
            hasErrors = true;
            errors.first_name_error = errorText;
        }
        if (!username) {
            hasErrors = true;
            errors.username_error = errorText;
        }
        if (!password) {
            hasErrors = true;
            errors.password_error = errorText;
        }
        if (!password_confirmation) {
            hasErrors = true;
            errors.password_confirmation_error = errorText;
        }
        if (!checked) {
            hasErrors = true;
            errors.checked_error = this.politicasErrorMessage;
        }

        if (password !== password_confirmation) {
            hasErrors = true;
            errors.password_confirmation_error = this.passwordError;
        }

        if (hasErrors) {
            this.setState(errors)
        }


        return !hasErrors;
    }

    _handleRegister() {
        if (this._formIsValid()) {
            let {
                last_name,
                first_name,
                username,
                password,
                password_confirmation,
            } = this.state;
            let componente = this;
            LoginSystem.register(
                username,
                password,
                password_confirmation,
                first_name,
                {
                    last_name: last_name,
                    tokenmovil: StoreReservation.getTokenMovil(),
                }, (err, success) => {
                    if (err) {
                        return componente.setState({
                            registro_error: err,
                        });
                    }
                    if (success) {
                        componente._goToNextStep();
                    }
                });
        }
    }

    _goToNextStep() {
        let store = this.props.store;
        store.setStep(this.props.next, this.props.next_name, true);
    }

    _checkPoliticas() {
        let resultado = !this.state.checked;
        this.setState({
            checked: !this.state.checked,
            checked_error: resultado ? '' : this.politicasErrorMessage
        });
    }

    _goToLogin() {
        let store = this.props.store;
        store.setStep(<LoginStepTemplate next={this.props.next} next_name={this.props.next_name}
                                         store={this.props.store}/>, 'LoginStepTemplate', true);
    }

    render() {
        let lang = StoreReservation.get('lang');
        return (
            <div className="WidgetBUQ--RegisterBlockContainer">
                <div>
                    <div className="WidgetBUQ--LoginBlockHeader">
                        <h2>{lang['widget.RegisterBlock.title']}</h2>
                        <p>{lang['widget.RegisterBlock.subtitle']}</p>
                    </div>
                    <div style={styles.form} className="WidgetBUQ--FormBlock">
                        <input
                            onChange={this._handleChange.bind(this, 'first_name')}
                            placeholder="Nombre"
                            value={this.state.first_name}
                            type="text"
                        />
                        <InputError text={this.state.first_name_error}/>
                        <input
                            onChange={this._handleChange.bind(this, 'last_name')}
                            placeholder="Apellidos"
                            value={this.state.last_name}
                            type="text"
                        />
                        <InputError text={this.state.last_name_error}/>
                        <input
                            onChange={this._handleChange.bind(this, 'username')}
                            placeholder="Correo"
                            value={this.state.username}
                            type="email"
                        />
                        <InputError text={this.state.username_error}/>
                        <input
                            onChange={this._handleChange.bind(this, 'password')}
                            placeholder="Contraseña"
                            value={this.state.password}
                            type="password"
                        />
                        <InputError text={this.state.password_error}/>
                        <input
                            onChange={this._handleChange.bind(this, 'password_confirmation')}
                            placeholder="Repetir contraseña"
                            value={this.state.password_confirmation}
                            type="password"
                        />
                        <InputError text={this.state.password_confirmation_error}/>
                        <div style={styles.politicas} className="WidgetBUQ--TermsBlock">
                            <input
                                type="checkbox"
                                value={this.state.checked}
                                onChange={this._checkPoliticas.bind(this)}
                            />
                            <p>{lang['widget.TermsBlock.label']}</p>
                        </div>
                        <InputError text={this.state.checked_error}/>
                        <button type="submit"
                                onClick={this._handleRegister.bind(this)}
                        >{lang['widget.LinkButton.accept']}
                        </button>
                        <InputError text={this.state.registro_error}/>
                    </div>

                    <div className="WidgetBUQ--RegisterBlock">
                        <button
                            className="WidgetBUQ--LinkButton"
                            onClick={this._goToLogin.bind(this)}
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
        color: 'red',
        fontSize: 30,
    },
    form: {
        // width: '100%'
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
    politicas: {
        flexDirection: 'row',
        alignItems: 'center',
    },
    politicasText: {},
    politicasTouch: {
        marginLeft: 10,
        paddingVertical: 15,
    },
    politicasTextBold: {
        fontFamily: 'larsseit-bold',
        borderColor: 'black',
        borderBottomWidth: 1,
    }
});
