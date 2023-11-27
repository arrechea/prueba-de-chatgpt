import React from 'react';
import StoreWidget from '../StoreWidget';
import IconText from '../common/IconText';
import ProfileElements from "../helpers/ProfileElements";
import PaymentElements from '../helpers/PaymentElements';

export default class Notification extends React.Component{

   constructor() {
      super();

      this.state={
         reservation_selected : null,
         information_saved: null,
         card_selected: null,
         card_saved: StoreWidget.card_saved,
      }

      this.updateStatus = this.updateStatus.bind(this);
      StoreWidget.addSegmentedListener('reservation_selected', this.updateStatus);
      StoreWidget.addSegmentedListener('information_saved', this.updateStatus);
      StoreWidget.addSegmentedListener('card_selected', this.updateStatus);
      StoreWidget.addSegmentedListener('card_saved', this.updateStatus);
   }

   updateStatus(){
      let cardSelected = StoreWidget.card_selected;
      let informationSaved = StoreWidget.information_saved;
      let reservationSelected = StoreWidget.reservation_selected;
      let cardSaved = StoreWidget.card_saved;

      this.setState({
         reservation_selected : reservationSelected,
         information_saved : informationSaved,
         card_selected : cardSelected,
         card_saved : cardSaved,
      });
   }

   handleClose(event){
      let curComp = this;
      let {reservation_selected, information_saved, card_selected, card_saved} = this.state;

      let segment;

      switch(true){
         case reservation_selected != null:
            segment = 'reservation_selected';
            break;
         case information_saved != null:
            segment = 'information_saved';
            break;
         case card_selected != null:
            segment = 'card_selected';
            break;
         case card_saved != null:
            segment = 'card_saved';
            break;
      }

      event.preventDefault();
      StoreWidget.closeNotification(segment, null);
   }

   handleCancel(event){
      event.preventDefault();
      const curComp = this;
      let curBrand = StoreWidget.current_brand.slug;
      let {reservation_selected} = this.state;

      ProfileElements.cancelReservation(
         curBrand,
         reservation_selected,
         '',
         function(){
            ProfileElements.getFutureReservations(
               curBrand,
               { reducePopulation: true }, 
               function (result) {
                  StoreWidget.setFutureClasses(result, function(){
                     StoreWidget.set('reservation_selected', null);
                  });
               }
            )
         }
      )
   }

   handleEraseCard(event){
      let {card_selected} = this.state;
      PaymentElements.removeConektaCard(
         card_selected.id,
         function(result){
            StoreWidget.setCreditCards(result, function(){
               StoreWidget.set('card_selected', null);
            });
         }
      )
   }
   
   render(){
      let {reservation_selected, information_saved, card_selected, card_saved} = this.state;

      return(
         <div className={'WidgetBUQ--Notification ' + (information_saved || reservation_selected || card_selected || card_saved ? 'is-active' : '')}>
            <button className="WidgetBUQ--Notification-cancel" onClick={this.handleClose.bind(this)}>
               <IconText
                  text=""
                  icon={<i className="far fa-times"></i>}
               /> 
            </button>

            <h3>
               {information_saved ? '¡Información Guardada!' : null}
               {reservation_selected ? '¿Estás seguro?' : null}
               {card_selected ? '¿Eliminar tarjeta?' : null}
               {card_saved ? '¡Tarjeta Guardada!' : null}
            </h3>

            <p>
               {information_saved ? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.' : null}
               {reservation_selected ? 'Tienes hasta 12 horas para cancelar tu clase. Una vez cancelada perderas tu lugar.' : null}
               {card_selected ? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit' + card_selected.last4 + ' ' + card_selected.brand +  ' .' : null}
               {card_saved ? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' : null}
            </p>
         
            { reservation_selected 
               ? <button className={'WidgetBUQ--buttonAccent'} onClick={this.handleCancel.bind(this)}>Cancelar mi clase :(</button> 
               : null}

            { card_selected 
               ?  <button className={'WidgetBUQ--buttonAccent'} 
                     onClick={this.handleEraseCard.bind(this)}
                  >
                        Borrar tarjeta :(
                  </button> 
               : null}
         </div>
      )
   }
}