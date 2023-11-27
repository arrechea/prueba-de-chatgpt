import StoreWidget from "../StoreWidget";
import Cipher from "../libraries/Cypher";
import StoreReservation from "../../reservation/process/StoreReservation";

const PaymentElements = {
   /**
      *
      * @param cb
   */
   getConektaToken(options, cb) {
      let store = StoreWidget;
      let user = StoreReservation.get('user');

      options = {
         card: Cipher.cifrar(JSON.stringify(options)),
      };

      if(user){
         GafaFitSDK.GetUserPaymentToken(
            store.current_brand.slug,
            store.current_location.slug,
            user.id,
            2,
            options,
            cb
         );
      } else {
         if (cb) {
            cb('Usuario no logueado');
         }
      }
   },


   /**
      *
      * @param token
      * @param phone
      * @param cb
   */
   addPaymentOptionConekta(token, phone, cb){
      let store = StoreWidget;
      let user = StoreReservation.get('user');

      if (user) {
         GafaFitSDK.PostUserAddPaymentOption(
            store.current_brand.slug,
            2,
            token,
            phone,
            function (error, result) {
               if (error === null) {
                  cb(result);
               }
            }
         )
     } else {
         if (cb) {
            cb('Usuario no logueado');
         }
     }
   },

   /**
      *
      * @param options
      * @param cb
   */
   getUserPayments(options, cb) {
      let curBrand = StoreWidget.current_brand.slug;
      GafaFitSDK.GetUserPaymentInfo(
         curBrand, options,
         function (error, result) {
               if (error === null) {
                  cb(result.conekta);
               }
         }
      )
   },

   /**
      *
      * @param idCard
      * @param cb
   */
   removeConektaCard(idCard, cb){
      let curBrand = StoreWidget.current_brand.slug;
      GafaFitSDK.PostUserRemovePaymentOption(
          curBrand,
          2,
          idCard,
          function (error, result) {
              if (error === null) {
                  cb(result.conekta);
              }
          }
      )
   }
}

export default PaymentElements;