import StoreReservation from "../StoreReservation";
import ErrorSDKParser from "./ErrorSDKParser";
const LoginSystem = {
    /**
     *
     * @param usuario
     * @param contrasenna
     * @param cb
     */
    login(usuario,
          contrasenna,
          cb){
        GafaFitSDK.GetToken(
            StoreReservation.getClientId(),
            StoreReservation.getClientSecret(),
            usuario,
            contrasenna,
            {
                grant_type: 'password',
                scope: '*'
            },
            (err, data) => {
                if (err) {
                    return cb(ErrorSDKParser.parseError(err));
                }
                let brand = StoreReservation.getBrand();
                let location = StoreReservation.location;
                let vars = (StoreReservation.getRequestMap()).vars;
                let options = {
                    forcejson: 'on',
                };
                for (let property in vars) {
                    options[property] = vars[property];
                }

                GafaFitSDK.GetCreateReservationForm(
                    brand.slug,
                    location.slug,
                    null,
                    null,
                    options,
                    (err, response) => {
                        if (!!response) {
                            StoreReservation.loguearConInfo(response, null, () => {
                                if (cb) {
                                    cb(err, response);
                                }
                            })
                        }
                    }
                );
            }
        );
    },
    /**
     *
     * @param username
     * @param password
     * @param password_confirmation
     * @param first_name
     * @param options
     * @param cb
     */
    register(username,
             password,
             password_confirmation,
             first_name,
             options,
             cb){

        GafaFitSDK.PostRegister(
            StoreReservation.getClientId(),
            StoreReservation.getClientSecret(),
            username,
            password,
            password_confirmation,
            first_name,
            options, (err, data) => {
                if (err) {
                    return cb(ErrorSDKParser.parseError(err));
                }
                LoginSystem.login(username, password, cb);
            }
        );
    },
    /**
     *
     * @param username
     * @param cb
     */
    passwordRecovery(username, cb){
      let sdk = window.GafaFitSDK;
      let urlSdk = sdk.getCurrentApiUrl();
      let company = StoreReservation.location.company.id;
      let url = urlSdk + 'cosmics/recovery-password/' + company + '/';

      GafaFitSDK.RequestNewPassword(
         username,
         url,//todo colocar url de buq donde se hace la recuperacion de contraseña!
         (err, data) => {
               if (err) {
                  return cb(ErrorSDKParser.parseError(err));
               } else {
                  cb(null, data)
               }
         }
      );
    }
};
export default LoginSystem;
