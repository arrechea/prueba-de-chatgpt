import React from 'react';
import ProfileElements from "../helpers/ProfileElements";
import StoreWidget from "../StoreWidget";
import ClassItem from '../common/ClassItem';
import PurchaseItem from '../common/PurchaseItem';
import PastClassItem from '../common/PastClassItem';
import FutureClasses from './FutureClasses';
import IconText from '../common/IconText';

export default class ShoppingList extends React.Component{
   constructor(p) {
      super(p);
      this.state={
         past_classes: [],
         user_purchase: [],
      }
   }

   componentDidMount() {
      const curComp = this;
      let curBrand = StoreWidget.current_brand.slug;

      ProfileElements.getHistoricReservations(
         curBrand,
         {  reducePopulation: true,
            per_page: 10000
         }, 
         function (result) {
            curComp.setState({
               past_classes: result.data,
            });
         }
      );

      ProfileElements.getUserPurchases(
         curBrand,
         {
            reducePopulation: true,
            per_page: 10000
         }, 
         function (result) {
            curComp.setState({
               user_purchase: result.data,
            });
         }
      );
   }

   render() {
      let {past_classes, user_purchase} = this.state;

      let pastClassList = past_classes.length > 0 
         ?  past_classes.map((reservation) => <PastClassItem key={reservation.id} reservation={reservation} id={reservation.id}/>)
         :  <div className="WidgetBUQ--EmptyData">
               <div className="WidgetBUQ--EmptyClass">
                  <p>Aún no tienes ninguna clase</p>
               </div>
               <button className={'WidgetBUQ--buttonAccent'}>Buq</button>
            </div>
      ;

      let userPurchaseList = user_purchase.length > 0 
         ?  user_purchase.map((purchase) => <PurchaseItem key={purchase.id} purchase={purchase} id={purchase.id}/>)
         :  <div className="WidgetBUQ--EmptyData">
               <div className="WidgetBUQ--EmptyClass">
                  <p>Aún no tienes ninguna compra</p>
               </div>
               <button className={'WidgetBUQ--buttonAccent'}>Buq</button>
            </div>
      ;

      return(
         <div className="WidgetBUQ--ShoppingList">
            <div className="WidgetBUQ--ShoppingList-section">
               <div className="WidgetBUQ--ShoppingList-title">
                  <h3>Mis próximas clases</h3>
               </div>
               <FutureClasses />
            </div>
            <hr></hr>
            <div className="WidgetBUQ--ShoppingList-section">
               <div className="WidgetBUQ--ShoppingList-title">
                  <h3>HISTORIAL DE CLASES</h3>
               </div>
               <div className="WidgetBUQ--ShoppingList-list">
                  {pastClassList}
               </div>
            </div>
            <hr></hr>
            <div className="WidgetBUQ--ShoppingList-section">
               <div className="WidgetBUQ--ShoppingList-title">
                  <h3>HISTORIAL DE COMPRAS</h3>
               </div>
               <div className="WidgetBUQ--ShoppingList-list">
                  {userPurchaseList}
               </div>
            </div>
         </div>
      )
   }
}