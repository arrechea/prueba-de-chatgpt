import React from 'react';
import Moment from 'moment';
import 'moment/locale/es';
import StoreWidget from "../StoreWidget";
import IconText from './IconText';

export default class ClassItem extends React.Component{
   constructor(props) {
      super(props);
   }

   postCancelReservation(event){
      event.preventDefault();
      let {reservation} = this.props;

      StoreWidget.postCancelReservation(
         reservation.id,
         null
      );
   }

   render(){
      let {reservation} = this.props;

      return(
         <div className="WidgetBUQ--NextClass">
            <button className="WidgetBUQ--NextClass-cancel" disabled={reservation.cancelled} onClick={this.postCancelReservation.bind(this)}>
               <IconText
                  text=""
                  icon={<i className="far fa-times"></i>}
               /> 
            </button>
            <p className="WidgetBUQ--NextClass-time">{Moment(reservation.meeting_start).format('h:mm a')}</p>
            <h4 className="WidgetBUQ--NextClass-staff">{reservation.staff.name}</h4>
            <p className="WidgetBUQ--NextClass-service">{reservation.service.name}</p>
            {
               reservation.cancelled 
                  ? <p className="WidgetBUQ--NextClass-cancelled">Cancelada</p>
                  : null
            }
         </div>
      )
   }
}