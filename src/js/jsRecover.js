   $(function() {

       //Escondemos los divs iniciales
       $("#loginError").hide();
       $("#loginLoader").hide();
       $("#loginMsg").hide();


       $("#recoverBtn").click(function(e) {


           errorFlag = false;
           e.preventDefault();

           $("#loginMsg").hide();
           $("#loginLoader").show();

           validaCampo("correo", "email");

           // alert("pass valida :: " + errorFlag);
           if (errorFlag == false) {

               var correo = $("#correo").val();
               $("#resultMessage").html("");

               var result = $.pwControll({
                   params: {
                       "class": "Recover",
                       "loader": "loginLoader",
                       "errormsg": "loginText",
                       "errorDiv": "loginMsg",
                       "correo": correo,
                       "mode": 2,
                       "encrypt": 1
                   },
               });
               /*result = $.parseJSON(result);
               if (result.status == "false") {
                   $("#loginLoader").hide();
                   errorFlag = true;
                   notify(result.value, "error");
                   return false;
               } else {

                   $("#loginLoader").hide();
                   //errorFlag = true;
                   //notify(result.value, "error");
                   $(".recover").html(result.value);
                   return false;
               }*/
           } else {
               $("#loginLoader").hide();
               return false;
           }
       });

   });

   $(document).ready(function() {

       $("#emailError").hide();
       $("#loginLoader").hide();
       $("#loginMsg").hide();

       $("#cpassword").attr('disabled', 'disabled');
       $("#btnChange").attr('disabled', 'disabled');

       $('#password').keyup(function() {
           // set password variable
           var pswd = $(this).val();
           var cont = 0;
           //pwdvalidate the length
           if (pswd.length < 8) {
               $('#length').removeClass('pwdvalid').addClass('pwdinvalid');
           } else {
               $('#length').removeClass('pwdinpwdvalid').addClass('pwdvalid');
               cont++;
           }

           //pwdvalidate small letter
           if (pswd.match(/[a-z]/)) {
               $('#small').removeClass('pwdinpwdvalid').addClass('pwdvalid');
               cont++;
           } else {
               $('#small').removeClass('pwdvalid').addClass('pwdinvalid');

           }

           //pwdvalidate capital letter
           if (pswd.match(/[A-Z]/)) {
               $('#capital').removeClass('pwdinpwdvalid').addClass('pwdvalid');
               cont++;
           } else {
               $('#capital').removeClass('pwdvalid').addClass('pwdinvalid');

           }

           //pwdvalidate number
           if (pswd.match(/\d/)) {
               $('#number').removeClass('pwdinpwdvalid').addClass('pwdvalid');
               cont++;
           } else {
               $('#number').removeClass('pwdvalid').addClass('pwdinvalid');

           }

           var characters = validateCharacters(pswd);

           if (characters == true) {
               $('#caracter').removeClass('pwdinvalid').addClass('pwdvalid');
               cont++;
           } else {
               $('#caracter').removeClass('pwdvalid').addClass('pwdinvalid');


           }
           //pswd_info

           if (cont == 5) {

               $("#cpassword").removeAttr('disabled');
               $("#btnChange").removeAttr('disabled');
               $('#pswd_info').hide();
           } else {
               $('#pswd_info').show();
               //$('#pswd_infoAux').hide();
               $("#cpassword").attr('disabled', 'disabled');
               $("#btnChange").attr('disabled', 'disabled');
           }

       }).focus(function() {
           $('#pswd_info').show();
       }).blur(function() {
           $('#pswd_info').hide();
           //$('#pswd_infoAux').hide();
       });


       /**
        * Boton para el cambio de contraseña
        */
       $("#changeBtn").click(function(e) {

           $("#cpasswordError").html("");
           $("#cpasswordError").hide();

           let clave1 = $('#password').val();
           let clave2 = $('#cpassword').val();
           let code = $('#code').val();
           //alert(code);

           //Si no coinciden las claves
           if (clave1 != clave2) {
               $("#cpasswordError").show().html("El password no coincide");
               $("#cpasswordError").parent().addClass('u-has-error-v1');
               return false;
           }

           var result = $.pwControll({
               params: {
                   "class": "Recover",
                   "loader": "loginLoader",
                   "errormsg": "loginText",
                   "errorDiv": "loginMsg",
                   "password": clave1,
                   "cpassword": clave2,
                   "code": code,
                   "mode": 4,
                   "encrypt": 1
               },
           });


       });

       /* $("#changeForm").submit(function(event) {
            var clave1 = $('#password').val();
            var clave2 = $('#cpassword').val();

            if (clave1 != clave2) {
                $.notify($("#cpassword"), "El password no coincide", { position: "right top", className: "error" });
                event.preventDefault();
                return false;
            }
        });*/

       /* $("#recMailForm").submit(function(event) {

            var correo = $('#correo').val();

            if ($.trim(correo).length == 0) {
                $.notify($("#correo"), "El campo no puede ir vacio", { position: "right top", className: "error" });
                event.preventDefault();
                return false;
            }

            if (!validateEmail(correo)) {
                $.notify($("#correo"), "El correo debe de ser válido", { position: "right top", className: "error" });
                event.preventDefault();
                return false;
            }

        });*/

       // Function that validates email address through a regular expression.
       /* function validateEmail(correo) {
            var filter = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            if (filter.test(correo)) {
                return true;
            } else {
                return false;
            }
        }*/



   });




   function validateCharacters(password) {
       var str = password;
       str = str.replace(/[A-Za-z0-9]/g, "");

       if (str == "") {
           return false;
       }

       var re = new RegExp("[^A-Za-z0-9(){}&$!¡¿?.:%_|°¬@/\=´¨+*~^,;]", "g");
       var myArray = re.exec(str);
       var result = false;

       if (myArray != null) {
           return false;
       }
       return true;
   }

   function goBack() {
       window.history.go(-1);
   }

   /*  function comprobarClave() {
         clave1 = document.getElementById('password').value;
         clave2 = document.getElementById('cpassword').value;

         if (clave1 != clave2) {
             //alert('El password no coincide');
             $("#difPass").css('display', 'block');
             return false;
         } else {
             $("#difPass").css('display', 'none');
             return true;
         }
     }*/