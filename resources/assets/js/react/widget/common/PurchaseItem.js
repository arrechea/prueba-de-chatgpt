import React from 'react';
import Moment from 'moment';
import 'moment/locale/es';
import IconText from './IconText';

export default class PurchaseItem extends React.Component{
   constructor(props) {
      super(props);
   }

   render(){

      let {purchase} = this.props;

      return(
         <div className="WidgetBUQ--Purchase">
            <h4 className="WidgetBUQ--Purchase-name"><strong>{purchase.items[0].item_name}</strong></h4>
            <h2 className={'WidgetBUQ--Purchase-price'}> $ {purchase.total}</h2>
            <p className={'WidgetBUQ--Purchase-time'}> {Moment(purchase.created_at).format('h:mm a DD/MM/YY')}</p>
         </div>
      )
   }
}