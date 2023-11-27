import * as React from 'react';

export default class AppSecretInput extends React.Component {
    constructor(props) {
        super();
        this.state = {
            lang: props.lang,
            type: 'password',
            button_text: props.lang.Show,
            secret: props.secret,
            url: props.url,
            load_secret: false,
            error: false,
            client_id: props.client_id,
            active: 'active',
        };

        this.showSecret = this.showSecret.bind(this);
        this.generate = this.generate.bind(this);
        this.Confirm = this.Confirm.bind(this);
        this.loader = this.loader.bind(this);
        this.error = this.error.bind(this);
    }

    showSecret(e) {
        if (this.state.type === 'password') {
            $('.label_client_secret').addClass('active');
            this.setState({
                type: 'text',
                button_text: this.state.lang.Hide
            })
        } else {
            this.setState({
                type: 'password',
                button_text: this.state.lang.Show
            })
        }
    }

    generate(e) {
        let component = this;

        $(document).ready(function () {
            component.setState({
                load_secret: true,
            });

            $.get(component.state.url).done(function (data) {
                let secret = data.secret;
                let id = data.id;
                $('.label_client_secret').addClass('active');
                component.setState({
                    secret: secret,
                    client_id: id,
                    load_secret: false,
                    error: false,
                });
            }).fail(function (e) {
                component.setState({
                    load_secret: false,
                    error: true,
                });
            });
        });

    }

    Confirm() {
        let component = this;

        $(document).ready(function () {
            let confirm_dialog = $(component.refs.confirm_dialog);
            confirm_dialog.modal('open');
        });
    }

    loader() {
        let component = this;
        if (component.state.load_secret) {
            return (
                <div className="progress" style={{position: 'absolute'}}>
                    <div className="indeterminate"></div>
                </div>
            );
        }
    }

    error() {
        let component = this;
        if (component.state.error) {
            return (
                <div className={'alert-danger'}>
                    {component.state.lang.GenerateError}
                </div>
            )
        }
    }

    render() {
        let component = this;

        return (
            <div className={'row'}>
                {this.error()}
                <div className={'col s12 m8'} id={'secret-key-container'}>
                    <div className={'col s2'} id={'client-id-container'}>
                        <div className={'input-field'}>
                            <input id={'client_id'} type={this.state.type} className={'input'} readOnly={true}
                                   defaultValue={this.state.client_id} value={this.state.client_id}
                                   onChange={e => this.Change(e.target.value)}/>
                            <label htmlFor={'client_id'}
                                   className={'label_client_secret'}>{this.state.lang.ClientID}</label>
                        </div>
                    </div>

                    <div className={'input-field col s10'} id={'secret-key-input'}>
                        {this.loader()}
                        <input id={'secret_key'} type={this.state.type} className={'input'} readOnly={true}
                               value={this.state.secret} onChange={e => this.Change(e.target.value)}/>
                        <label htmlFor={'secret_key'}
                               className={'label_client_secret'}>{this.state.lang.ClientSecretKey}</label>
                    </div>
                </div>
                <div className={'col s12 m4'} id={'secret-key-buttons-container'}>
                    <div className={'secret-buttons'} style={{position: 'relative'}}>
                        <a className={'repeat-day secret-key-button col s6 m6 l6'} onClick={component.showSecret}
                           style={{position: 'absolute', bottom: 0, top: '25px'}}>{this.state.button_text}</a>
                        <a className={'repeat-day secret-key-button col s6 m6 l6'}
                           style={{
                               position: 'absolute',
                               bottom: '0',
                               left: '55%',
                               top: '25px'
                           }} ref={'generate_button'} onClick={this.Confirm}>{this.state.lang.Generate}</a>
                    </div>
                </div>
                <div id={'confirm-dialog'} className={'modal modaldelete'} ref={'confirm_dialog'}>
                    <div className={'modal-content'}>
                        <h5 className="header" style={{left: '35px'}}>{this.state.lang.ConfirmGeneration}</h5>

                        <a className="s12 modal-action modal-close waves-effect waves-green btn btndelete"
                           onClick={this.generate}>
                            {this.state.lang.Generate}
                        </a>
                    </div>
                </div>
            </div>
        );
    }
}
