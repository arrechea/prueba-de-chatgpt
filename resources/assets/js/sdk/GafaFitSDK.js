import GafaFitRequests from "./GafaFitRequests";
import $ from 'jquery'

var GafaFitSDK = function () {
    let Requests = new GafaFitRequests();
    let company = null;
    let isPluginWordpressActivated = false;
    let errorString = 'La información esta incompleta, porfavor revisar que los datos esten completos y sean correctos.';

    return {
        /**
         *
         * @param activated
         */
        setPluginWordpressActivated(activated){
            isPluginWordpressActivated = activated;
        },
        /**
         *
         * @returns {boolean}
         */
        isPluginWordpressActivated(){
            return isPluginWordpressActivated === true;
        },
        /**
         * Set the current url
         * @param {string} url
         */
        setUrl(url) {
            Requests.setUrl(url);
        },
        /**
         * Set Production mode
         */
        setProductionMode() {
            Requests.setProductionMode();
        },
        /**
         * Set Local mode to https://gafafit.test/
         */
        setLocalMode() {
            Requests.setLocalMode();
        },
        /**
         * Get current API url
         * @returns {string}
         */
        getCurrentApiUrl() {
            return Requests.getCurrentApiUrl();
        },
        /**
         * Set company to work in
         * @param {number} newCompany
         */
        setCompany(newCompany) {
            company = newCompany;
            Requests.setCompany(newCompany);
        },

        /**
         * Set autorization token
         * @param newToken
         */
        setAutorization(newToken) {
            Requests.setAutorization(newToken);
        },
        /**
         * Check if user is authentificated
         * @returns {boolean}
         */
        isAuthentified(callback) {
            // return Requests.isAuthentified();
            this.GetMe(function (err, user) {
                let auth = !e;
                callback(auth, err, user);
            });
        },


        /**
         * Get a token to log into
         *
         * @param {number} client_id
         * @param {string} client_secret
         * @param {string} username
         * @param {string} password
         * @param {object} options -- 'grant_type', 'scope'
         * @param {function} callback
         */
        GetToken(client_id, client_secret, username, password, options, callback) {
            if (!client_id || !client_secret || !username || !password) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            let url = 'oauth/token';
            let params = {
                'grant_type': options['grant_type'],
                'client_id': client_id,
                'client_secret': client_secret,
                'username': username,
                'password': password,
                'scope': options['scope']

            };
            let sdk = this;
            Requests.request('post', url, params, function (err, data) {
                if (!err && data) {
                    sdk.setAutorization(data.access_token);
                }
                if (callback) {
                    callback(err, data)
                }
            });
        },

        /**
         * Get a token for a new password
         *
         * @param {string} email
         * @param {url} return_url
         * @param {function} callback
         */
        RequestNewPassword(email, return_url, callback) {
            if (!email || !return_url) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                }
                return console.error(error);
            }
            let url = 'api/password/email';
            let options = {
                'email': email,
                'return_url': return_url
            };
            Requests.request('post', url, options, callback);
        },

        /**
         * Register form submit
         *
         * @param {number} client_id
         * @param {string} client_secret
         * @param {string} username
         * @param {string} password
         * @param {string} password_confirmation
         * @param {string} first_name
         * @param {object} options -- 'grant_Type', 'scope', 'last_name','shoe_size', 'birth_date', 'gender'
         * @param {function} callback
         */
        PostRegister(client_id,
                     client_secret,
                     username,
                     password,
                     password_confirmation,
                     first_name,
                     options,
                     callback) {
            if (!client_id || !client_secret || !username || !password || !password_confirmation || !first_name) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            let url = 'api/register';
            let params = {
                'grant_type': options['grant_type'],
                'client_id': client_id,
                'client_secret': client_secret,
                'username': username,
                'password': password,
                'scope': options['scope'],
                'password_confirmation': password_confirmation,
                'first_name': first_name,
                'last_name': options['last_name'],
                'shoe_size': options['shoe_size'],
                'birth_date': options['birth_date'],
                'gender': options['gender'],
                'tokenmovil': options['tokenmovil'],
                'g_recaptcha_response': options['g_recaptcha_response'],
                'g-recaptcha-response': options['g_recaptcha_response'],
                'captcha_secret_key': options['captcha_secret_key'],
                'remote_addr': options['remote_addr'],
                'custom_fields': options['custom_fields']
            };
            Requests.request('post', url, params, callback);
        },

        /**
         * Request a new password for the user
         *
         * @param {string} email
         * @param {string} password
         * @param {string} password_confirmation
         * @param {string} token
         * @param {function} callback
         */
        NewPassword(email, password, password_confirmation, token, callback) {

            if (!email || !password || !password_confirmation || !token) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = 'api/password/reset';
            let params = {
                'email': email,
                'password': password,
                'password_confirmation': password_confirmation,
                'token': token
            };
            Requests.request('post', url, params, callback);
        },

        /**
         * Staff's List of Brands
         *
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        GetBrandStaffList(brand, options, callback) {

            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/staff`;
            Requests.request('get', url, options, callback);
        },

        /**
         * Staff's Create
         *
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        PostBrandStaffCreate(brand, options, callback) {

            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/staff/`;
            Requests.request('post', url, options, callback);
        },

        /**
         * Staff's Update
         *
         * @param {integer} id
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        PostBrandStaffUpdate(id, brand, options, callback) {

            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/staff/${id}/update`;
            Requests.request('post', url, options, callback);
        },

        /**
         * Staff's Delete
         *
         * @param {integer} id
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        BrandStaffDelete: function (id, brand, options, callback) {

            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/staff/${id}`;
            Requests.request('delete', url, options, callback);
        },

        /**
         * Staff's restore
         *
         * @param {integer} id
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        GetBrandStaffRestore(id, brand, options, callback) {

            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/staff/${id}/restore`;
            Requests.request('get', url, options, callback);
        },

        /**
         * Specific Staff Information
         *
         * @param {string} brand
         * @param {string} staff
         * @param {object} options -- 'page', 'per_page'
         * @param {function} callback
         */
        GetBrandStaff(brand, staff, options, callback) {
            if (!brand || !staff) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            let url = `api/brand/${brand}/staff/${staff}`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Meetings of an staff in the brand
         *
         * @param {string} brand
         * @param {string} staff
         * @param {object} options -- {Date} 'start' , {Date} 'end'
         * @param {function} callback
         */
        GetBrandStaffNextMeetings(brand, staff, options, callback) {
            if (!brand || !staff) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/staff/${staff}/next-meetings`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Special Text of an Staff
         *
         * @param {string} brand
         * @param {string} staff
         * @param {object} options
         * @param {function} callback
         */
        GetStaffSpecialTexts(brand, staff, options, callback) {
            if (!brand || !staff) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/staff/${staff}/special-text`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Service List of brand
         *
         * @param {string} brand
         * @param {object} options -- 'page', 'per_page','only_actives', 'only_parents'
         * @param callback
         */
        GetBrandServiceList(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/service`;
            Requests.request('get', url, options, callback)
        },

        /**
         *Specific Service Information
         *
         * @param {string} brand
         * @param {number} serviceToSee
         * @param {function} callback
         */
        GetBrandService(brand, serviceToSee, callback) {
            if (!brand || !serviceToSee) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/service/${serviceToSee}`;
            Requests.request('get', url, '', callback)
        },

        /**
         * Special Text of Services
         *
         * @param {string} brand
         * @param {number} serviceToSee
         * @param {function} callback
         */
        GetBrandServiceSpecialText(brand, serviceToSee, callback) {
            if (!brand || !serviceToSee) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/service/${serviceToSee}/special-text`;
            Requests.request('get', url, '', callback)
        },

        /**
         * Services Meetings in the brand
         *
         * @param {string} brand
         * @param {number} serviceToSee
         * @param {object} options --  {Date} 'start' , {Date} 'end'
         * @param {function} callback
         */
        GetBrandServiceNextMeetings(brand, serviceToSee, options, callback) {
            if (!brand || !serviceToSee) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/service/${serviceToSee}/meetings`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Combos List in the brand
         *
         * @param {string} brand
         * @param {object} options -- 'page', 'per_page', 'only_actives', 'propagate'
         * @param {function} callback
         */
        GetBrandCombolist(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/combos`;
            Requests.request('get', url, options, callback)
        },

        /**
         * List of combos of a user
         *
         * @param {string} brand
         * @param {object} options -- 'page', 'per_page', 'only_actives', 'propagate'
         * @param {function} callback
         */
        GetBrandComboListforUser(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            let url = `api/brand/${brand}/combos/userPosibilities`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Membership List in a brand
         *
         * @param {string} brand
         * @param {object} options -- 'page', 'per_page', 'only_actives', 'propagate'
         * @param {function} callback
         */
        GetBrandMembershipList(brand, options, callback) {

            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            let url = `api/brand/${brand}/membership`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Brand List in the company
         *
         * @param {object} options -- 'page','per_page','only_actives'
         * @param {function} callback
         */
        GetBrandList(options, callback) {

            let url = `api/brand`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Specific Brand Information
         *
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        GetBrand(brand, options, callback) {

            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            let url = `api/brand/${brand}`;
            Requests.request('get', url, options, callback)
        },

        /**
         *Country List
         *
         * @param {object} options
         * @param {function} callback
         */
        GetCountryList(options, callback) {
            let url = `api/places/countries`;
            Requests.request('get', url, options, callback)
        },

        /**
         * City List
         *
         * @param {object} options
         * @param {string} country
         * @param {function} callback
         */
        GetCountryCityList(country, options, callback) {
            if (!country) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/places/countries/${country}/cities`;
            Requests.request('get', url, options, callback)
        },

        /**
         * States List
         *
         * @param {string} country
         * @param {object} options
         * @param {function} callback
         */
        GetCountryStateList(country, options, callback) {
            if (!country) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/places/countries/${country}/states`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Locations List in a brand
         *
         * @param {string} brand
         * @param {object} options -- 'page','per_page','only_actives'
         * @param {function}callback
         */
        GetBrandLocationList(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/location`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Specific Location Information
         *
         * @param {string} brand
         * @param {number} locationToSee
         * @param {object} options
         * @param {function} callback
         */
        GetBrandLocation(brand, locationToSee, options, callback) {
            if (!brand || !locationToSee) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            let url = `api/brand/${brand}/location/${locationToSee}`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Meetings of a room
         *
         * @param {string} brand
         * @param {number} locationToSee
         * @param {number} room
         * @param {object} options -- {Date} 'start', {Date} 'end'
         * @param {function} callback
         */
        GetRoomMeetingList(brand, locationToSee, room, options, callback) {

            if (!brand || !locationToSee || !room) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            let url = `api/brand/${brand}/location/${locationToSee}/rooms/${room}/meetings`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Meetings List of location
         *
         * @param {string} brand
         * @param {number} locationToSee
         * @param {object} options -- {Date} 'start', {Date} 'end'
         * @param {function} callback
         */
        GetlocationMeetingList(brand, locationToSee, options, callback) {

            if (!brand || !locationToSee) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            let url = `api/brand/${brand}/location/${locationToSee}/meetings`;
            Requests.request('get', url, options, callback)
        },
        /**
         *
         * @param brand
         * @param cssSelector
         * @param options
         * @param callback
         * @returns {*}
         * @constructor
         */
        GetCreateWidget(brand, cssSelector, options, callback){
            let elementToAppend = $(cssSelector);
            let error = Requests.setStringToError(errorString);
            if (!brand) {
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            let url = `api/brand/${brand}/widget`;
            Requests.request('get', url, options, function (err, data) {
                if (elementToAppend.length === 1) {
                    elementToAppend.html(data);
                }

                if (callback) {
                    if (elementToAppend.length === 1) {
                        callback(err, true);
                    } else {
                        callback(err, data)
                    }
                }
            })
        },
        /**
         * Modal of reservation confirmation and buying combos and membership.
         *
         * @param {string} brand
         * @param {string} location
         * @param {number} users_id
         * @param {string} cssSelector -- html element where the response is printed
         * @param {object} options -- 'combos_id', membership_id, meetings_id
         * @param {function} callback
         */
        GetCreateReservationForm(brand, location, users_id, cssSelector, options, callback) {
            let elementToAppend = $(cssSelector);
            let error = Requests.setStringToError(errorString);
            if (!brand || !location) {
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }
            if (options) {
                options.users_id = users_id;
            } else {
                options = {
                    users_id: users_id
                };
            }
            let url = `api/brand/${brand}/location/${location}/reservation/create-form-template`;
            Requests.request('get', url, options, function (err, data) {
                if (elementToAppend.length === 1) {
                    elementToAppend.html(data);
                }

                if (callback) {
                    if (elementToAppend.length === 1) {
                        callback(err, true);
                    } else {
                        callback(err, data)
                    }
                }
            })
        },


        /**
         * Membership list of a user
         *
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        GetBrandMembershipListForUser(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/membership/userPosibilities`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Redeem Gift Card to user.
         *
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        PostRedeemGiftCard(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }


            let url = `api/me/giftcards/brand/${brand}/redeem`;
            Requests.request('post', url, options, callback)
        },

        /**
         * Purchases List in the location
         *
         * @param {number} locationToSee
         * @param {object} options -- 'page', 'per_page'
         * @param {function} callback
         */
        GetUserPurchasesInLocation(locationToSee, options, callback) {
            if (!locationToSee) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }


            let url = `api/me/location/${locationToSee}/purchases`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Purchases List of a user in the brand
         *
         * @param {string} brand
         * @param {object} options -- 'page', 'per_page'
         * @param {function} callback
         */
        GetUserPurchasesInBrand(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }


            let url = `api/me/brand/${brand}/purchases`;
            Requests.request('get', url, options, callback)
        },
        /**
         * Specific Purchase of a user.
         *
         * @param {string} brand
         * @param {number} purchase
         * @param {object} options
         * @param {function} callback
         */
        GetUserPurchase(brand, purchase, options, callback) {

            if (!brand || !purchase) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/brand/${brand}/purchases/${purchase}`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Past Reservations of a user
         *
         * @param {string} brand
         * @param {object} options -- 'page', 'per_page'
         * @param {function} callback
         */
        GetUserPastReservationsInBrand(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/brand/${brand}/reservation-past`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Future reservations of a user.
         *
         * @param {string} brand
         * @param {object} options -- 'reducePopulation
         * @param {function} callback
         */
        GetUserFutureReservationsInBrand(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/brand/${brand}/reservation-future`;
            Requests.request('get', url, options, callback)
        },
        /**
         * Reservation Information.
         *
         * @param {string} brand
         * @param {number} reservation
         * @param {object} options
         * @param {function} callback
         */
        GetUserReservation(brand, reservation, options, callback) {
            if (!brand || !reservation) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/brand/${brand}/reservations/${reservation}`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Cancel a Reservation
         *
         * @param {string} brand
         * @param {number} reservation
         * @param {object} options
         * @param {function} callback
         */
        PostUserCancelReservation(brand, reservation, options, callback) {
            if (!brand || !reservation) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/brand/${brand}/reservation-future/${reservation}/cancel`;
            Requests.request('post', url, options, callback)
        },


        /**
         * Payment Token of User.
         *
         * @param brand
         * @param location
         * @param users_id
         * @param paymentMethod
         * @param options
         * @param callback
         * @returns {*}
         * @constructor
         */
         GetUserPaymentToken(brand, location, users_id, paymentMethod, options, callback) {
            let error = Requests.setStringToError(errorString);
            if (!brand || !location || !users_id) {
               if (callback) {
                  return callback(error)
               } else {
                  return console.error(error);
               }
            }
            let url = `cosmics/${brand}/${location}/${users_id}/get-payment-token/${paymentMethod}`;
            Requests.request('get', url, options, (err, data) => {
               if (callback) {
                  callback(err, data)
               }
            });
         },

        /**
         * Payment Method of user.
         *
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        GetUserPaymentInfo(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/payments/brand/${brand}/payment-methods`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Remove Payment Method
         *
         * @param {string} brand
         * @param {number} paymentMethod
         * @param {string} idCard
         * @param {function} callback
         */
        PostUserRemovePaymentOption(brand, paymentMethod, idCard, callback) {
            if (!brand || !paymentMethod || !idCard) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }


            let url = `api/me/payments/brand/${brand}/method/${paymentMethod}/remove`;
            let params = {
                'option': idCard,
            };
            Requests.request('post', url, params, callback)
        },

        /**
         * Add a new Payment Method
         *
         * @param {string} brand
         * @param {number} paymentMethod
         * @param {string} optionToken
         * @param {string} optionPhone
         * @param {function} callback
         */

        PostUserAddPaymentOption(brand, paymentMethod, optionToken, optionPhone, callback) {
            if (!brand || !paymentMethod || !optionToken || !optionPhone) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }


            let url = `api/me/payments/brand/${brand}/method/${paymentMethod}`;
            let params = {
                'option': {
                    'token': optionToken,
                    'phone': optionPhone,
                },
            };
            Requests.request('post', url, params, callback)
        },

        /**
         * User's Credits
         *
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        GetUserCredits(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/brand/${brand}/credits`;
            Requests.request('get', url, options, callback)
        },

        /**
         * User's Membership
         *
         * @param {string} brand
         * @param {object} options
         * @param {function} callback
         */
        GetUserMembership(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/brand/${brand}/memberships`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Wait List of a user
         *
         * @param {string} brand
         * @param {object} options --'reducePopulation'
         * @param {function} callback
         */
        GetUserFutureWaitlistInBrand(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/brand/${brand}/waitlist/future`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Past Wait List of a user
         *
         * @param {string} brand
         * @param {object} options --'reducePopulation'
         * @param {function} callback
         */
        GetUserPastWaitlistInBrand(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/brand/${brand}/waitlist/past`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Cancel a Wait List
         *
         * @param {string} brand
         * @param {number} waitlist
         * @param {object} options
         * @param {function} callback
         */
        PostUserCancelWaitlistInBrand(brand, waitlist, options, callback) {
            if (!brand || !waitlist) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/me/brand/${brand}/waitlist/remove/${waitlist}`;
            Requests.request('post', url, options, callback)
        },

        /**
         * Log out session
         *
         * @param {object} options
         * @param {function} callback
         */
        PostLogout(options, callback) {

            let url = `api/logout`;
            Requests.request('post', url, options, function (err, data) {
                if (callback) {
                    callback(err, data);
                }
                if (!err) {
                    Requests.logout();
                }
            })
        },

        /**
         * Get all User
         *
         * @param {function} callback
         */
        GetUsers(callback) {
            let url = `api/users`;
            Requests.request('get', url, '', callback)
        },
        /**
         * Create User
         *
         * @param {object} options
         * @param {function} callback
         */
        CreateUser(options, callback) {
            let url = `api/users`;
            Requests.request('post', url, options, callback)
        },
        /**
         * Update User
         *
         * @param {object} options
         * @param {function} callback
         */
        UpdateUser(options, user_id, callback) {
            let url = `api/users/${user_id}/update`;
            Requests.request('post', url, options, callback)
        },

        /**
         * Remove an existent user.
         *
         * @param {function} callback
         */
        DeleteUser(user_id, callback) {
            let url = `api/users/${user_id}`;
            Requests.request('delete', url, '', callback)
        },

        /**
         * Restore a deleted user
         *
         * @param {function} callback
         */
        RestoreUser(user_id, callback) {
            let url = `api/users/${user_id}/restore`;
            Requests.request('get', url, '', callback)
        },

        /**
         * Current User Information
         *
         * @param {function} callback
         */
        GetMe(callback) {
            let url = `api/me`;
            Requests.request('get', url, '', callback)
        },

        /**
         * Update User Information
         *
         * @param {object} options
         * @param {function} callback
         */
        PutMe(options, callback) {

            let url = `api/me`;
            Requests.request('post', url, options, callback)
        },

        /**
         * Get groups with fields from special texts
         *
         * @param {integer} catalog
         * @param {string} brand (optional)
         * @param {object} options (uses variable 'section')
         * @param {function} callback
         */
        GetCatalogSpecialTextsGroupsWithFields(catalog, brand, options, callback) {
            if (!catalog) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/special-text/form/${catalog}/${brand}`;
            Requests.request('get', url, options, callback);
        },

        /**
         * Get values for a certain record in a catalog and a section
         *
         * @param {integer} catalog
         * @param {integer} model_id
         * @param {string} brand (optional)
         * @param {object} options (uses variable 'section')
         * @param {function} callback
         */
        GetCatalogSpecialTextsValues(catalog, model_id, brand = '', options = '', callback) {
            if (!catalog || !model_id) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/special-text/values/${catalog}/${model_id}/${brand}`;
            Requests.request('get', url, options, callback);
        },

        /**
         * Get rooms for a particular brand
         *
         * @param {string} brand
         * @param {object} options (optional)
         * @param {function} callback
         */
        GetRoomsInBrand(brand, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/room`;
            Requests.request('get', url, options, callback)
        },

        /**
         * Get rooms for a particular location
         *
         * @param {string} brand
         * @param {string} location
         * @param {object} options (optional)
         * @param {function} callback
         */
        GetRoomsInLocation(brand, location, options, callback) {
            if (!brand) {
                let error = Requests.setStringToError(errorString);
                if (callback) {
                    return callback(error)
                } else {
                    return console.error(error);
                }
            }

            let url = `api/brand/${brand}/room/location/${location}`;
            Requests.request('get', url, options, callback)
        }
    }
};


export default GafaFitSDK;
