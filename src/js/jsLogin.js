 $(function() {

     //Escondemos los divs iniciales
     $("#loginError").hide();
     $("#passwordError").hide();
     $("#loginLoader").hide();
     $("#loginMsg").hide();


     $("#loginBtn").click(function(e) {

         errorFlag = false;
         e.preventDefault();

         $("#loginMsg").hide();
         $("#loginLoader").show();

         validaCampo("login");
         validaCampo("password");
      

         if (errorFlag == false) {
             var login = $("#login").val();
             var password = $("#password").val();

             $("#resultMessage").html("");

           
             var result = $.pwControll({
                 params: {
                     "class": "Login",
                     "loader": "loginLoader",
                     "errormsg": "loginText",
                     "errorDiv": "loginMsg",
                     "login": login,
                     "password": password,
                     "mode": 2,
                     "encrypt": 1
                 },
             });

             /*	result = $.parseJSON(result);
        
      if (result.status == "true")
		  {        
        location.reload(true);
		  }
      else if(result.status == "false")
      {
         notify(result.value, "error");
      }   */

         } else {

             $("#loginLoader").hide();
             return false;
         }
     });

 });