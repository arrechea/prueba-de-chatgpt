import axios from 'axios'
import CryptoJS from 'crypto-js'

var GafaFitRequests = function () {
    let current_url = 'https://devgafa.fit/';

    //OtrasUrls
    let base_url = 'https://gafafit.test/';
    // let base_url = 'https://gafafit.local:9009/';
    let production_url = 'https://gafa.fit/';

    //Extra data
    let company = null;
    let autorizationKey = 'z9kFLKUk@5SF8FD*J*Lz';
    let autorization = checkAutorization();

    /**
     *Validating token information
     *
     * @returns {object} EncryptedToken
     */
    function checkAutorization() {
        let gafafitSDKAutorization = localStorage.getItem('gafafitSDKAutorization');
        if (gafafitSDKAutorization) {
            let bytes = CryptoJS.AES.decrypt(gafafitSDKAutorization.toString(), autorizationKey);
            return bytes.toString(CryptoJS.enc.Utf8);
        }
        return null;
    }


    return {
        setUrl(url){
            current_url = url;
        },
        getCurrentApiUrl() {
            return current_url;
        },
        setProductionMode() {
            current_url = production_url;
        },
        setLocalMode() {
            current_url = base_url;
        },
        setCompany(newCompany) {
            company = newCompany;
        },
        setAutorization(newToken) {
            autorization = newToken;
            let ciphertext = CryptoJS.AES.encrypt(newToken, autorizationKey);
            localStorage.setItem('gafafitSDKAutorization', ciphertext);
        },
        logout() {
            autorization = null;
            localStorage.removeItem('gafafitSDKAutorization');
        },
        isAuthentified() {
            return !!autorization;
        },

        setStringToError(string) {
            return {
                'error': [
                    string
                ]
            }
        },

        request(method, path, options, callback) {

            let component = this;
            let defaultHeaders = {
                'Accept': 'application/json',
                'GAFAFIT-COMPANY': company
            };

            if (!company) {
                return callback(component.setStringToError('Favor de ingresar compañia'));
            }
            if (autorization) {
                defaultHeaders['Authorization'] = `Bearer ${autorization}`;
            }
            let config = {
                headers: defaultHeaders,
                method: method,
            };
            if (method === 'get') {
                config.params = options;
            } else {
                config.data = options;
            }

            let finalUrl = `${current_url}${path}`;


            var request = axios.create({
                baseURL: finalUrl,
                timeout: 10000,
            });

            request.request(config)
                .then(function (response) {
                    // console.log(response.data);
                    // console.log(response.status);
                    // console.log(response.statusText);
                    // console.log(response.headers);
                    // console.log(response.config);
                    if (callback) {
                        callback(null, response.data);
                    }
                })
                .catch(function (error) {
                    let errorText = '';
                    // handle error
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        if (!!error.response.data.errors) {
                            errorText = error.response.data.errors;
                        } else if (!!error.response.data.error) {
                            errorText = error.response.data.error;
                        } else {
                            errorText = error.response.data.message;
                        }
                        // console.log(error.response.data);
                        // console.log(error.response.status);
                        // console.log(error.response.headers);
                    } else if (error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        // console.log(error.request);
                        errorText = error.request;
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        // console.log('Error', error.message);
                        errorText = error.message;
                    }
                    if (typeof errorText === 'string') {
                        errorText = component.setStringToError(errorText)
                    }

                    if (callback) {
                        callback(errorText);
                    }
                });
        }
    }
};


export default GafaFitRequests;
