import StoreWidget from "../StoreWidget";
import StoreReservation from "../../reservation/process/StoreReservation";

const ProfileElements = { 


   /**
      * 
      * @param brand
      * @param options
      * @param cb
   */
   getFutureReservations(brand, options, cb){
      GafaFitSDK.GetUserFutureReservationsInBrand(
         brand, 
         options,
         (err, response) => {
            if(!err && cb){
               cb(response);
            }
         }
      );
   },


   /**
      * 
      * @param cb
   */
   getCountries(cb) {
      GafaFitSDK.GetCountryList({},
         function (error, result) {
            if (error === null) {
               cb(result);
            } else {
               cb([]);
            }
         }
      );
   },
   
   /**
      * 
      * @param c // Country 
      * @param cb // Callback
   */
   getCountryStates(c, cb) {
      GafaFitSDK.GetCountryStateList(c, {},
         function (error, result) {
            if (error === null) {
               cb(result);
            } else {
               cb([]);
            }
         }
      );
   },

   /**
      * 
      * @param brand
      * @param reservation
      * @param options
      * @param cb
   */
   cancelReservation(brand, reservation, options, cb){
      GafaFitSDK.PostUserCancelReservation(
         brand,
         reservation,
         options,
         (err, response) => {
            if(!err && cb){
               cb(response);
            }
         }
      );
   },

   /**
      * 
      * @param params
      * @param successCb
      * @param errorCb
   */
   putMe(params, successCb, errorCb) {
      GafaFitSDK.PutMe(
         params,
         function (error, result) {
            if (error != null) {
               let errorToPrint = Object.keys(error).map(function (key) {
                     return error[key];
               }).join(". ");
               errorCb(errorToPrint);
            } else {
               StoreReservation.set('user', result);
               successCb();
            }
         }
      );
   },

   /**
      * 
      * @param brand
      * @param options
      * @param cb
   */
   getHistoricReservations(brand, options, cb){
      GafaFitSDK.GetUserPastReservationsInBrand(
         brand, 
         options,
         (err, response) => {
            if(!err && cb){
               cb(response);
            }
         }
      );
   },

   /**
      * 
      * @param brand
      * @param options
      * @param cb
   */
   getUserPurchases(brand, options, cb){
      GafaFitSDK.GetUserPurchasesInBrand(
         brand, 
         options,
         (err, response) => {
            if(!err && cb){
               cb(response);
            }
         }
      );
   }
}

export default ProfileElements;