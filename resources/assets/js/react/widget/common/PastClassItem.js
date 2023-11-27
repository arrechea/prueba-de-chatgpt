import React from 'react';
import Moment from 'moment';
import 'moment/locale/es';
import IconText from './IconText';

export default class PastClassItem extends React.Component{
   constructor(props) {
      super(props);
   }

   render(){
      let {reservation} = this.props;

      return(
         <div className="WidgetBUQ--PastClass">
            <p className="WidgetBUQ--PastClass-time">{Moment(reservation.meeting_start).format('DD/MM/YY')}</p>
            {/* <p className="WidgetBUQ--PastClass-time">{Moment(reservation.meeting_start).format('h:mm a')}</p> */}
            <h4 className="WidgetBUQ--PastClass-staff">{reservation.staff.name}</h4>
            <p className="WidgetBUQ--PastClass-service">{reservation.service.name}</p>
         </div>
      )
   }
}