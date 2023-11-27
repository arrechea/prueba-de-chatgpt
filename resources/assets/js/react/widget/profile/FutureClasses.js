import React from 'react';
import ProfileElements from "../helpers/ProfileElements";
import StoreWidget from "../StoreWidget";
import ClassItem from '../common/ClassItem';
import PurchaseItem from '../common/PurchaseItem';
import PastClassItem from '../common/PastClassItem';
import IconText from '../common/IconText';

export default class FutureClasses extends React.Component{
   constructor(p) {
      super(p);
      this.state={
         future_classes: [],
      }

      StoreWidget.addSegmentedListener('future_classes', this.updateClasses.bind(this));
   }

   componentDidMount() {
      const curComp = this;
      let curBrand = StoreWidget.current_brand.slug;
      
      ProfileElements.getFutureReservations(
         curBrand,
         {
            reducePopulation: true
         }, 
         function (result) {
            StoreWidget.setFutureClasses(result, null)
         }
      );
   }

   updateClasses(){
      let futureClasses = StoreWidget.future_classes;

      this.setState({
         future_classes: futureClasses,
      });
   }

   render() {
      let future_classes = StoreWidget.future_classes;

      let futureClassList = future_classes.length > 0 
         ?  future_classes.map((reservation) => <ClassItem key={reservation.id} reservation={reservation} id={reservation.id}/>)
         :  <div className="WidgetBUQ--EmptyData">
               <div className="WidgetBUQ--EmptyClass">
                  <p>Aún no tienes ninguna clase</p>
               </div>
               <button className={'WidgetBUQ--buttonAccent'}>Buq</button>
            </div>
      ;

      return(
         <div className="WidgetBUQ--ShoppingList-list">
            {futureClassList}
         </div>
      )
   }
}