import React from 'react';
import StoreReservation from "../../../reservation/process/StoreReservation";
import PaymentElements from "../../helpers/PaymentElements";
import Form from "react-bootstrap/Form";
import StoreWidget from '../../StoreWidget';

export default class CreateCreditCard extends React.Component {
    constructor() {
        super();

        this.state = {
            iframe: null,
            error: null,
            number: '',
            name: '',
            exp_year: '',
            exp_month: '',
            cvc: '',
            phone: '',
            card_saving: StoreWidget.card_saving
        };
        window.WidgetPostMessageCosmos = this.handleMessage.bind(this);

        StoreWidget.addSegmentedListener('card_saving', this.updateSaving.bind(this));
    }

    componentDidMount() {
        let user = StoreReservation.get('user');

        let userPhone, userName;

        if (user) {
            if (user.phone) {
                userPhone = user.phone;
            } else if (user.cel_phone) {
                userPhone = user.cel_phone;
            }

            if (user.first_name && user.last_name) {
                userName = user.first_name + " " + user.last_name;
            }
        }
        ;

        this.setState({
            name: userName,
            phone: userPhone
        });
    }

    updateSaving(){
       let cardSaving = StoreWidget.card_saving;
       
       this.setState({
          card_saving: cardSaving
       })
    }

    /**
     *
     * @param error
     */
    imprimirErrores(error) {
        this.setState({
            error: error
        });
    }

    saveCard(event) {
        event.preventDefault();
        StoreWidget.set('card_saving', true);

        let curComp = this;
        let {
            number,
            name,
            exp_year,
            exp_month,
            cvc,
            phone,
        } = this.state;

        if (
            !number ||
            !name ||
            !exp_year ||
            !exp_month ||
            !cvc ||
            !phone
        ) {
            this.imprimirErrores('Completa todos los campos del formulario');
            return null;
        }

        PaymentElements.getConektaToken({
            number,
            name,
            exp_year,
            exp_month,
            cvc,
        }, (err, url) => {
            if (err) {
                this.imprimirErrores(err);
                return null;
            }
            curComp.setState({
               iframe: url,
            });
        });
    }

    handleMessage(data) {
      let curComp = this;
      let {phone} = this.state;
      let success = JSON.parse(data);

      //todo trabajar desde aqui
      // console.log('decifrado', data);

      PaymentElements.addPaymentOptionConekta(
         success.token, 
         phone,
         function(data){
            console.log('success');
            StoreWidget.cardSaved();
            curComp.props.closeTab();
            StoreWidget.setCreditCards(data.conekta, null);
         }
      );
   }

   errorAddCard(error){
      console.log(error);
   }

    Decipher(salt) {
        let textToChars = text => text.split('').map(c => c.charCodeAt(0));
        let saltChars = textToChars(salt);
        let applySaltToChar = code => textToChars(salt).reduce((a, b) => a ^ b, code);
        return encoded => encoded.match(/.{1,2}/g)
            .map(hex => parseInt(hex, 16))
            .map(applySaltToChar)
            .map(charCode => String.fromCharCode(charCode))
            .join('')
    }

    handleChangeField(event) {
        let fieldName = event.target.id;
        let fieldValue = event.target.value;
        this.setState({
            [fieldName]: fieldValue
        });
    }

    render() {
        let {number, name, exp_year, exp_month, cvc, phone, iframe, card_saving} = this.state;

        return (
            <div>
                <form onSubmit={this.saveCard.bind(this)}>
                    <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="number">
                        <Form.Label>Número de tarjeta</Form.Label>
                        <Form.Control
                            type="number"
                            value={number}
                            onChange={this.handleChangeField.bind(this)}
                        />
                    </Form.Group>
                    <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="name">
                        <Form.Label>Nombre de la tarjeta</Form.Label>
                        <Form.Control
                            type="text"
                            value={name}
                            onChange={this.handleChangeField.bind(this)}
                        />
                    </Form.Group>
                    <div className="WidgetBUQ--form-col3">
                        <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="exp_year">
                            <Form.Label>Año</Form.Label>
                            <Form.Control
                                type="number"
                                value={exp_year}
                                onChange={this.handleChangeField.bind(this)}
                            />
                        </Form.Group>
                        <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="exp_month">
                            <Form.Label>Mes</Form.Label>
                            <Form.Control
                                type="number"
                                value={exp_month}
                                onChange={this.handleChangeField.bind(this)}
                            />
                        </Form.Group>
                        <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="cvc">
                            <Form.Label>CVC</Form.Label>
                            <Form.Control
                                type="number"
                                value={cvc}
                                onChange={this.handleChangeField.bind(this)}
                            />
                        </Form.Group>
                    </div>

                    <Form.Group bsPrefix={'WidgetBUQ--form-group'} controlId="phone">
                        <Form.Label>Phone</Form.Label>
                        <Form.Control
                            type="number"
                            value={phone}
                            onChange={this.handleChangeField.bind(this)}
                        />
                    </Form.Group>

                    <button className={'WidgetBUQ--saveButton ' + (card_saving ? ' is-saving' : '')} type="submit">
                        Guardar
                    </button>
                </form>

                  {iframe
                     ? <iframe style={{'display': 'none'}} srcDoc={iframe}/>
                     : null
                  }
            </div>
        )
    }
}
