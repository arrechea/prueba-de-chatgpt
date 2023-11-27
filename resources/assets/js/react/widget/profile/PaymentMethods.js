import React from 'react';
import StoreWidget from "../StoreWidget";
import Cypher from "../libraries/Cypher";
import IconText from '../common/IconText';
import PaymentElements from "../helpers/PaymentElements";
import CreditCard from "./UserPayment/CreditCard";
import CreateCreditCard from "./UserPayment/CreateCreditCard";


export default class PaymentMethods extends React.Component{
   constructor() {
      super();

      this.state={
         card_list: null,
         addNew: false,
      }

      StoreWidget.addSegmentedListener('creditCards', this.updateCards.bind(this));
   }

   componentDidMount(){
      let curComp = this;
      PaymentElements.getUserPayments('', function (result) {
         StoreWidget.setCreditCards(result, null)
      });
   }

   handleAddCard(event){
      event.preventDefault();

      this.setState({
         addNew : !this.state.addNew,
      })
   }

   closeAddNew(){
      this.setState({
         addNew: false
      })
   }

   updateCards(){
      let cardList = StoreWidget.creditCards;

      this.setState({
         card_list: cardList,
      });
   }

   render() {
      let {card_list, addNew} = this.state;
      
      let credit_cards = card_list
         ?  card_list.map((card, index) => {
               return <CreditCard key={index} card={card}/>
            })
         :  null;

      return(
         <div className="WidgetBUQ--PaymentMethods">
            <div className="WidgetBUQ--PaymentMethods-title">
               <h3>Métodos de pago</h3>
            </div>

            <div className="WidgetBUQ--PaymentMethods-section">
               <div className="WidgetBUQ--PaymentMethods-cardList">
                  {credit_cards}
                  <div className="WidgetBUQ--CreditCard-addNew">
                     <p>Agregar nueva tarjeta</p>

                     <button className={'WidgetBUQ--CreditCard-addbtn'} 
                        onClick={this.handleAddCard.bind(this)}
                     >
                        <IconText
                           text=""
                           icon={<i className="far fa-plus"></i>}
                        /> 
                     </button>
                  </div>
               </div>

               {addNew 
                  ?
                     <div className="WidgetBUQ--PaymentMethods-addNew">
                        <CreateCreditCard closeTab={this.closeAddNew.bind(this)}/>
                     </div>

                  : null
               }
            </div>
         </div>
      )
   }
}