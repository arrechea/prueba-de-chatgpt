<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{env('APP_URL')}}/sdk/dist/main.js?v={{env('APP_VERSION')}}"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
      * {
         padding: 0;
         margin: 0;
         box-sizing: border-box;
         font-family: 'DM Sans', sans-serif;
      }

      input[type="password"]{
         border:0 transparent none;
         font-size:14px;
         padding:8px 12px;
         border-bottom: #D4D3D3 solid 1px;
         outline: none;
      }

      input[type="password"]:focus{
         border-color:#575757;
      }

      input[type="submit"]{
         border:0;
         border-radius:23px;
         padding: 15px 0;
         font-size:14px;
         color:white;
         background-color: #FF3E4F;
         outline: none;
         cursor: pointer;
      }

      input[type="submit"]:hover{
         background-color: #FFA3AB;
      }

      p, label{
         font-size: 12px;
         color:#76748A;
      }

      label{
         color: #ACACAC;
      }

      .buq-recovery{
         display:grid;
         place-items:center;
         height: 100vh;
         width: 100vw;
      }

      .buq-recovery__container{
         display:inline-grid;
         row-gap: 1.6rem;
         max-width:100%;
         padding:20px;
      }

      @media only screen and (min-width: 768px){
         .buq-recovery__container{
            max-width:400px;
            padding:0px;
         }
      }

      #buq-recovery__form{
         display:grid;
         row-gap: 1rem;
      }

      #buq-recovery__form input[type="submit"]{
         margin-top:1rem;
      }

      .buq-recovery__formGroup{
         display:grid;
      }

      .buq-recovery__content h1,
      .buq-recovery__content p{
         line-height: 1.6
      }

      .buq-recovery__icon{
         width:90px;
         color:#FF3E4F;
      }

      .buq-recovery__notify{
         position: relative;
         box-sizing: border-box;
         height:80px;
         width:100%;
      }

      .buq-recovery__notify-message{
         position:absolute;
         opacity:0;
         pointer-events: none;
         top:0;
         left:0;
         border: 2px solid grey;
         padding: 15px;
         box-sizing: border-box;
         border-radius:100px;
         width:100%;
         transform: translateY(10px);
         transition: all .3s ease;
      }

      .buq-recovery__notify-message.is-active{
         display:block;
         opacity:1;
         transform: translateY(0px);
         pointer-events: all;
      }

      .buq-recovery__notify-message p{
         color: inherit;
      }

      .buq-recovery__notify-message.is-success{
         border-color: #5AA700;
         color: #5AA700;
      }

      .buq-recovery__notify-message.is-error{
         border-color: #FF3E4F;
         color: #FF3E4F;
      }
    </style>
</head>
<body>
<!-- <div id="fancy"></div> -->

<div class="buq-recovery">
   <div class="buq-recovery__container">
      <div class="buq-recovery__icon">
         <svg width="100%" height="100%" viewBox="0 0 54 28.392" xmlns="http://www.w3.org/2000/svg" class="buq-icon" xmlnsXlink="http://www.w3.org/1999/xlink">
            <g fill="currentColor">
               <path d="M39.407,5.01a9.09,9.09,0,0,0-4.172,1.009,3.78,3.78,0,0,1,.331,1.545V8.818a6.507,6.507,0,0,1,8.859,1.114,6.26,6.26,0,0,1-.806,8.752,6.51,6.51,0,0,1-8.919-.47,11.338,11.338,0,0,1-1.3,2.313,9.118,9.118,0,0,0,12.435-.4v6.433a1.288,1.288,0,0,0,1.3,1.277h0a1.288,1.288,0,0,0,1.3-1.277V13.893A8.97,8.97,0,0,0,39.407,5.01Z" />
               <path d="M8.907,5.054A8.882,8.882,0,0,0,2.56,7.71V1.277A1.279,1.279,0,0,0,1.28,0h0A1.278,1.278,0,0,0,0,1.277V13.94A8.907,8.907,0,1,0,8.907,5.054Zm0,15.216a6.331,6.331,0,1,1,6.347-6.331A6.339,6.339,0,0,1,8.907,20.271Z" />
               <path d="M31.547,6.124a1.3,1.3,0,0,0-1.3,1.293v6.412A6.437,6.437,0,0,1,19.112,18.2a11.624,11.624,0,0,1-1.3,2.343,9.029,9.029,0,0,0,15.031-6.716V7.417A1.3,1.3,0,0,0,31.547,6.124Z" />
               <path d="M52.33,25.192a1.53,1.53,0,1,1-1.53,1.53,1.53,1.53,0,0,1,1.53-1.53m0-.14A1.67,1.67,0,1,0,54,26.722a1.67,1.67,0,0,0-1.67-1.67Z" />
               <path d="M52.26,26.165a.066.066,0,0,0-.058.03l-.429.589-.424-.589a.066.066,0,0,0-.028-.021.069.069,0,0,0-.033,0,.069.069,0,0,0-.072.066v.975a.065.065,0,0,0,.019.045.06.06,0,0,0,.046.019.063.063,0,0,0,.049-.019.065.065,0,0,0,.019-.045v-.8l.364.516a.072.072,0,0,0,.026.017.055.055,0,0,0,.03,0,.05.05,0,0,0,.03,0l.026-.017.362-.508v.789a.065.065,0,0,0,.019.045.078.078,0,0,0,.053.019.075.075,0,0,0,.051-.019.059.059,0,0,0,.021-.045V26.24a.057.057,0,0,0-.021-.047.094.094,0,0,0-.049-.028Z" />
               <path d="M52.987,26.713l.437-.452a.064.064,0,0,0,.019-.04.046.046,0,0,0-.025-.038.088.088,0,0,0-.063-.017.077.077,0,0,0-.055.029l-.407.425-.412-.425a.09.09,0,0,0-.066-.026.111.111,0,0,0-.058.017.056.056,0,0,0-.025.045.052.052,0,0,0,.016.038l.437.455-.434.457a.064.064,0,0,0-.022.043.049.049,0,0,0,.022.036.079.079,0,0,0,.055.019.075.075,0,0,0,.063-.028l.41-.425.412.425a.082.082,0,0,0,.066.028.1.1,0,0,0,.055-.017.053.053,0,0,0,.027-.047.059.059,0,0,0-.016-.038Z" />
            </g>
         </svg>
      </div>
      <div class="buq-recovery__content">
         <h1>Restablecer contraseña</h1>
         <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
      </div>
      <form id="buq-recovery__form">
         <input type="hidden" id="buq-recovery_token"  value="{{$token}}"></input>
         <input type="hidden" disabled id="buq-recovery_email" value="{{$email}}"></input>
         <div class="buq-recovery__formGroup">
            <label>Contraseña nueva</label>
            <input type="password" id="buq-recovery_pss"></input>
         </div>
         <div class="buq-recovery__formGroup">
            <label>Confirmar contraseña</label>
            <input type="password" id="buq-recovery_pss-conf"></input>
         </div>
         <input type="submit" id="buq-submit-pss"></input>
         <div class="buq-recovery__notify">
            <div class="buq-recovery__notify-message is-success">
               <p>Lorem ipsum dolor sit ammet</p>
            </div>
            <div class="buq-recovery__notify-message is-error">
               <p>Error: Lorem ipsum dolor sit ammet</p>
            </div>
         </div>
      </form>
   </div>
</div>

<script>
    (function () {
        let sdk = window.GafaFitSDK;
      //   let brand = 'zuda';//todo eliminar
      //   let location = 'zuda-plaza-lilas';//todo eliminar
        sdk.setUrl('{{env('APP_URL')}}/');
        sdk.setCompany({{$companyOfUser}});

        document.getElementById('buq-recovery__form').addEventListener('submit', function(e){
            e.preventDefault();
            let token = document.getElementById('buq-recovery_token').value;
            let email = document.getElementById('buq-recovery_email').value;
            let pss = document.getElementById('buq-recovery_pss').value;
            let pss_conf = document.getElementById('buq-recovery_pss-conf').value;

            $("#buq-submit-pss").attr("disabled", true);
            
            console.log(token, email);

            sdk.NewPassword(email, pss, pss_conf, token, function(error, data){
               if(error){
                  document.querySelector('.is-error').classList.add("is-active");

                  setTimeout(function(){
                     document.querySelector('.is-error').classList.remove("is-active");
                     $("#buq-submit-pss").attr("disabled", false);
                  }, 3000);

               } else{
                  document.querySelector('.is-success').classList.add("is-active");
                  
                  setTimeout(function(){
                     document.querySelector('.is-success').classList.remove("is-active");
                     $("#buq-submit-pss").attr("disabled", false);
                  }, 3000);
               }
            })
        })
        // sdk.setAutorization('eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImJjNzBmNzFiNTAwYzAxNWQ0MTZhZGI0ZDkxYTg3NjVjNGU5M2QyYzE2OWQ5ZWU4OThlNGNkMzYxMzExZjc4YWFjZWI4Y2ZjOGVhMzJkYzAzIn0.eyJhdWQiOiIzIiwianRpIjoiYmM3MGY3MWI1MDBjMDE1ZDQxNmFkYjRkOTFhODc2NWM0ZTkzZDJjMTY5ZDllZTg5OGU0Y2QzNjEzMTFmNzhhYWNlYjhjZmM4ZWEzMmRjMDMiLCJpYXQiOjE1OTA3NzU0NzIsIm5iZiI6MTU5MDc3NTQ3MiwiZXhwIjoxNjIyMzExNDcyLCJzdWIiOiIxMTcwOCIsInNjb3BlcyI6WyIqIl19.WemUaym1sTpTRmizxR3iPQaHigFZ8JILCxypsn9pZKgtU__UUfPvqhimB_ykBsqOfXVFig4gb7V6Gico0yii48LEQQPhf6YpgHm6ag_y6-DQfDCAvhNUM0SdBkGXbEQbu3wD2w1xt27PdwMwwhvpoEFvYB6bO4WCsfoARfRe-7szDETXSgIlNe_U541l1VByK9dPhuDP-eSXjweSKuxkWNOaFZveuZxRte2vvQ1CPXopeHpPHgwxVeWa0ZV2o8_JolT_dCspwrGcZJ4UIGb6OP60C2YpCovHZe7suIEYCiGfye3FV0YKlRH9dOq3ZkosBGOZnJ0b8bEtv8A2ywFXyQHzMd09M1waLYMW6F7HYvqwMQjmA3xuRNqv5WbmFDTsa5ERiY_1hOaaTMtQsvBOB1X4jjicIvdbLAQEyBDQ0yf03R5sTyp1tDEVkLZ_MzYpe0uhJVfPHmBmr_fuLrd7rLE8ktvT2A81GZaWqNFkAgRQNL3epV7FWIpO-N6f-AwxyTsTigvqlT2hoRaDTyLGbrYJ0RlgiKZEoY8YXLnGnVq5a_qF5wIf_VzCB_a_l8wFL78dBpsf8Sh_UoLbhG0M1h9wpnIfluLBNRpWDFU7lPzhrnI7JR1MOML4Y6pQY5_G2_pIuoqZOvjW9gC0so4D1Tzc7s6Cr5dvL5COOwMXraU');
//         sdk.GetCreateWidget(brand, '#fancy', {//todo eliminar esto y solamente usar el link de recuperacion de contraseña al querer recuperar sdk.NewPassword
// //            meetings_id: 18226
//         }, function (err, data) {
//             if (err) {
//                 console.log(err);
//             }
//         });
//        sdk.GetCreateReservationForm(brand, location, null, '#fancy', {
////            meetings_id: 18222
//        }, function (err, data) {
//            if (err) {
//                console.log(err);
//            }
//        });
    })()
</script>
</body>
</html>
