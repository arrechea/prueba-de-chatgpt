import React from 'react'
import InputError from "../helpers/InputError";
import RegisterStepTemplate from "./RegisterStepTemplate";
import LoginSystem from "../helpers/LoginSystem";
import ForgotPasswordStepTemplate from "./ForgotPasswordStepTemplate";
import StoreReservation from "../StoreReservation";

export default class LoginStepTemplate extends React.Component {
    constructor(props) {
        super(props);
        let store = props.store;
        let user = StoreReservation.get('user');
        if (user && !!props.next) {
            store.setStep(props.next, props.next_name, true);
            return;
        }
        this.state = {
            usuario: 'mario@rehilete.com.mx',//todo Eliminar
            usuario_error: '',
            contrasenna: 'Wisquimas86',//todo Eliminar
            contrasenna_error: '',
            logueo_error: '',
        };
    }

    _goToNextStep() {
        let store = this.props.store;
        store.setStep(this.props.next, this.props.next_name, true);
    }

    _handleLogin() {
        let {
            usuario,
            contrasenna
        } = this.state;

        if (!usuario || !contrasenna) {
            return alert('Completa los campos del formulario');
        }
        let componente = this;
        LoginSystem.login(
            usuario,
            contrasenna,
            (err, success) => {
                if (err) {
                    return componente.setState({
                        logueo_error: err
                    });
                }
                if (!!success) {
                    componente._goToNextStep();
                }
            }
        );
    }

    _handleChange(input, e) {
        let text = e.target.value;
        let newState = {
            [input]: text
        };
        if (text === '') {
            newState[`${input}_error`] = "Por favor, completa este campo";
        } else {
            newState[`${input}_error`] = "";
        }
        this.setState(newState);
    }

    _goToRegister() {
        let store = this.props.store;
        store.setStep(<RegisterStepTemplate next={this.props.next} next_name={this.props.next_name}
                                            store={this.props.store}/>, 'RegisterStepTemplate', true);
    }

    _goToForgotPassword() {
        let store = this.props.store;
        store.setStep(<ForgotPasswordStepTemplate next={this.props.next} next_name={this.props.next_name}
                                                  store={this.props.store}/>, 'ForgotPasswordStepTemplate', true);
    }

    render() {
        let lang = StoreReservation.get('lang');

        return (
            <div style={styles.loginBlockContainer} className="WidgetBUQ--LoginBlockContainer">
                <div>
                    <div className="WidgetBUQ--LoginBlockHeader">
                        <h2>{lang['widget.loginBlock.title']}</h2>
                        <p>{lang['widget.loginBlock.subtitle']}</p>
                    </div>
                    <div style={styles.loginBlock} className="WidgetBUQ--FormBlock">
                        <input
                            onChange={this._handleChange.bind(this, 'usuario')}
                            placeholder="Usuario"
                            value={this.state.usuario}
                            type="text"
                        />
                        <InputError text={this.state.usuario_error}/>
                        <input
                            onChange={this._handleChange.bind(this, 'contrasenna')}
                            placeholder="Contraseña"
                            value={this.state.contrasenna}
                            type="password"
                        />
                        <InputError text={this.state.contrasenna_error}/>
                        <button type="submit" onClick={this._handleLogin.bind(this)}
                        >  {lang['widget.LinkButton.accept']}
                        </button>
                        {
                            this.state.logueo_error ? (
                                <div>${this.state.logueo_error}</div>
                            ) : null
                        }
                    </div>
                    <div style={styles.registro} className="WidgetBUQ--PasswordBlock">
                        <button
                            className="WidgetBUQ--LinkButton"
                            style={styles.registroTextBottom}
                            onClick={this._goToForgotPassword.bind(this)}
                        >
                            {lang['widget.PasswordBlock.link']}
                        </button>
                    </div>
                    <div style={styles.registro} className="WidgetBUQ--RegisterBlock">
                        <button
                            className="WidgetBUQ--LinkButton"
                            style={styles.registroTextBottom}
                            onClick={this._goToRegister.bind(this)}
                        >
                            {lang['widget.RegisterBlock.link']}

                        </button>
                    </div>
                </div>
            </div>
        )
    }
}
const styles = ({
    arrow: {
        width: 60,
        height: 60,
        position: 'absolute',
        top: 0,
        left: 20,
    },
    container: {
        //   flex: 1,
        //   alignItems: 'center',
        //   paddingBottom: 30,
    },
    background: {
        flex: 1,
        height: 330,
        position: 'absolute',
        top: -30,
        left: -10,
        zIndex: -1,
    },
    logoContainer: {
        width: 166,
        height: 51,
        color: 'red',
        marginTop: 87,
    },
    loginBlockContainer: {
        //   flex: 1,
        //   marginTop: 93,
        //   width: '100%',
    },
    loginBlock: {
        //   backgroundColor: 'white',
        //   marginHorizontal: 10,
        //   paddingVertical: 10,
        //   paddingHorizontal: 25,
    },
    registro: {
        //   marginTop: 20,
        //   flexDirection: 'row',
        //   alignItems: 'center',
        //   justifyContent: 'center',
        //   flex: 1,
    },
    registroText: {},
    registroTextBottom: {
        //   marginLeft: 10,
        //   paddingVertical: 20,
    },
    registroTextBottomText: {
        //   fontFamily: 'larsseit-bold'
    },
});
