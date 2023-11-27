import React from 'react';
import StoreWidget from "../../StoreWidget";
import IconText from '../../common/IconText';

export default class CreditCard extends React.Component{

   handleEraseCard(){
      let {card} = this.props

      let cardSelected = {
         'id': card.id,
         'last4': card.last4,
         'brand': card.brand
      }
      StoreWidget.setCardSelected(cardSelected, null);
   }

   render(){
      let {card} = this.props

      return(
         <div className={'WidgetBUQ--CreditCard'}>
            <div className={'WidgetBUQ--CreditCard-data'}>
               <p>**** **** **** {card.last4}</p>
               <div className="WidgetBUQ--CreditCard-brand">
                  {card.brand === "mastercard" ? <i className="fab fa-cc-mastercard"></i> : null}
                  {card.brand === "visa" ? <i className="fab fa-cc-visa"></i> : null}
                  {card.brand === "american_express" ? <i className="fab fa-cc-amex"></i> : null}
               </div> 
            </div>
            <button className={'WidgetBUQ--CreditCard-cancelbtn'} onClick={this.handleEraseCard.bind(this)}>
               <IconText
                  text=""
                  icon={<i className="far fa-times"></i>}
               /> 
            </button>
         </div>
      )
   }
}