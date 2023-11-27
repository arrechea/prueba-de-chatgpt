import React from 'react'
import AbstractStore from "../common/AbstractStore";

const StoreWidget = {
   segmentedlisteners: [],
   uid: null,
   locations: [],
   color: null,
   images: {},
   lang: {},
   step: null,
   backholder: 'none',
   current_location: null,
   current_brand: null,
   meetings_id: null,//Se utiliza para declarar un meeting por defecto

   future_classes: [],
   reservation_selected: null,
   
   information_saved: null,
   information_changed: false,

   card_selected: null,
   card_saving: false,
   card_saved: false,
   creditCards: null,

   goToStep(step, cb){
      StoreWidget.set('step', step, cb);
   },

   addSegmentedListener(sg, cb) {
      if (sg && cb) {
         if (sg.length) {
            //array
            sg.forEach(function (i) {
               GlobalStorage.subscribeToSegment(i, cb);
            })
         } else {
            //no array
            GlobalStorage.subscribeToSegment(sg, cb);
         }
      }
   },

   subscribeToSegment(sg, cb) {

      if (!this.segmentedListeners.hasOwnProperty(sg)) {
         this.segmentedListeners[sg] = [];
      }
      this.segmentedListeners[sg].push(cb);
   },

   closeNotification(segment, cb){
      StoreWidget.set(segment, null, function(){
         if(segment === 'information_saved'){
            StoreWidget.set('information_changed', false, cb);
         } else  {
            cb
         }
      });
   },

   cardSaved(cb){
      StoreWidget.set('card_saving', false, function(){
         StoreWidget.set('card_saved', true, cb);
      });
   },

   setFutureClasses(data, cb){
      StoreWidget.set('future_classes', data, cb);
   },

   setCreditCards(data, cb){
      StoreWidget.set('creditCards', data, cb);
   },

   postCancelReservation(id, cb){
      StoreWidget.set('reservation_selected', id, cb);
   },

   setCardSelected(data, cb){
      StoreWidget.set('card_selected', data, cb);
   },

   savedMe(value, cb){
      StoreWidget.set('information_saved', value, cb);
   },

   abrirWidget(cb) {
      if (cb) {
         cb();
      }
   }
};

Object.assign(StoreWidget, AbstractStore);

export default StoreWidget;
