<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="notLoaded">
<head>
    <title>{{ isset($company)?$company->name:config('app.name', 'Forge Admin') }} {{$title??''}}</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
</head>
<body>
<form id="form" style="display: none"></form>
<div id="payment_types" style="display: none">{{$payment_types or '[]'}}</div>

<script>
    (function () {
        awaitPostMessage();
        let JSONElement = document.getElementById('payment_types');
        var errores = false;
        var payments = null;
        var form = document.getElementById('form');
        var globalSalt = 'MeetingManager';

        try {
            payments = JSON.parse(JSONElement.textContent);
        } catch (e) {
            errores = true;
        }

        function getPublicKey() {
            let conektaLocal = buscarConekta();
            if (conektaLocal) {
                let config = conektaLocal.config;

                if (config) {
                    let enviroment = getEnviroment(config);

                    let sandbox = config.development_public_api_key;
                    let production = config.production_public_api_key;

                    if (enviroment === 'development' && !sandbox) {
                        return null;
                    }
                    if (enviroment === 'production' && !production) {
                        return null;
                    }

                    return enviroment === 'production' ? production : sandbox;
                }
            }
            return null;
        }

        function getConfig(pivot) {
            return pivot.config;
        }

        function getEnviroment(config) {
            let response = 'development';

            if (config) {
                let type = config.type;
                switch (type) {
                    case 'production':
                        response = 'production';
                        break;
                }
            }

            return response;
        }

        function buscarConekta() {
            let respuesta = null;
            if (Object.keys(payments).length) {
                Object.keys(payments).forEach(function (id) {
                    let payment = payments[id];
                    if (payment.slug === 'conekta') {
                        respuesta = payment;
                    }
                })
            }
            return respuesta;
        }

        function awaitPostMessage() {
            let isReactNativePostMessageReady = !!window.originalPostMessage;
            const queue = [];
            let currentPostMessageFn = function store(message) {
                if (queue.length > 100) queue.shift();
                queue.push(message);
            };
            if (!isReactNativePostMessageReady) {
                // const originalPostMessage = window.postMessage;
                Object.defineProperty(window, 'postMessage', {
                    configurable: true,
                    enumerable: true,
                    get() {
                        return currentPostMessageFn;
                    },
                    set(fn) {
                        currentPostMessageFn = fn;
                        isReactNativePostMessageReady = true;
                        setTimeout(sendQueue, 0);
                    }
                });
            }

            function sendQueue() {
                while (queue.length > 0) window.postMessage(queue.shift());
            }
        }

        /**
         *
         * @param salt
         * @returns {function(*)}
         */
        function Decipher(salt) {
            let textToChars = text => text.split('').map(c => c.charCodeAt(0));
            let saltChars = textToChars(salt);
            let applySaltToChar = code => textToChars(salt).reduce((a, b) => a ^ b, code);
            return encoded => encoded.match(/.{1,2}/g)
                .map(hex => parseInt(hex, 16))
                .map(applySaltToChar)
                .map(charCode => String.fromCharCode(charCode))
                .join('')
        }

        function Cipher(salt) {
            let textToChars = text => text.split('').map(c => c.charCodeAt(0))
            let byteHex = n => ("0" + Number(n).toString(16)).substr(-2)
            let applySaltToChar = code => textToChars(salt).reduce((a, b) => a ^ b, code)

            return text => text.split('')
                .map(textToChars)
                .map(applySaltToChar)
                .map(byteHex)
                .join('')
        }

        function init() {
            armarForm();

            let publicKey = getPublicKey();
            if (publicKey) {
                Conekta.setPublicKey(publicKey);
                let Cypher = new Cipher(globalSalt);

                Conekta.Token.create(
                    form,
                    function success(token) {
                        let respuesta = JSON.stringify({
                            success: true,
                            token: token.id,
                        });
                        if (!!window.ReactNativeWebView) {
                            window.ReactNativeWebView.postMessage(Cypher(respuesta));
                        } else if (!!window.parent && !!window.parent.WidgetPostMessageCosmos) {
                            window.parent.WidgetPostMessageCosmos((respuesta));
                        } else {
                            window.postMessage(Cypher(respuesta));
                        }
                    },
                    function error(response) {
                        let respuesta = JSON.stringify({
                            success: false,
                            token: response.message_to_purchaser,
                        });
                        if (!!window.ReactNativeWebView) {
                            window.ReactNativeWebView.postMessage(Cypher(respuesta));
                        } else if (!!window.parent && !!window.parent.WidgetPostMessageCosmos) {
                            window.parent.WidgetPostMessageCosmos((respuesta));
                        } else {
                            window.postMessage(Cypher(respuesta));
                        }
                    }
                );
            } else {
                console.error('No hay public key');
            }
        }

        function armarForm() {
            let myDecipher = Decipher(globalSalt);
            let card = myDecipher("{{$card}}");

            try {
                var jsonCard = JSON.parse(card);
            } catch (e) {
                return null;
            }
            for (let property in jsonCard) {
                if (jsonCard.hasOwnProperty(property)) {
                    let input = document.createElement("input");
                    input.setAttribute('data-conekta', 'card[' + property + ']');
                    input.setAttribute('value', jsonCard[property]);
                    form.appendChild(input);
                }
            }
        }

        if (!errores && form) {
            console.log('Iniciando');
            init();
        } else {
            console.error('Errores');
        }
    })()
</script>
</body>
</html>
