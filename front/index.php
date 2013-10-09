<?php include('template/header.php'); ?>

<script type="text/javascript">
    function verifyLogin(){
        var data = null;

        var email = document.getElementsByName( "email" )[0].value;
        var pwd = document.getElementsByName( "password" )[0].value;  

        $.ajax({
            type : 'POST',           
            url : server_url + 'application/login', // Servlet URL           
            data:{
                'email':email,
                'pwd':pwd
            },
            timeout: 10000,
            dataType: 'jsonp',
            success : function(data) {
                if(data.logged_in){
                    //We set up the Secuity Key
                    setCookie("key", data.user.key, 1);
                    
                    //Redirect
                    window.location.href="home.php";

                } else {
                    alert("Invalid Login!!");
                        console.log( data );
                    if( data.errors ) {
                        //define
                        var error = {};
                        error.alert = data.errors;

                        //Append
                        var template = Handlebars.compile( $('#alertTemplate').html() );
                        $('#errors').empty().append( template(error) );

                        //Erase
                        error = {};
                    }
                }
            },
            error : function( xhr, type )
            {
                alert('server error occurred');
            } 
        });
    }
</script>

<div class="content-box">
    <div class="content">
        <div class="container no-bottom">
            <div class="contact-form no-bottom">
                <div id="errors">
                    <!-- Handlebars -->
                </div>
                <div class="loginForm" id="loginForm" />
                    <div class="formFieldWrap">
                        <label class="field-title loginUserField" for="loginUserField">Your Email</label>
                        <input type="text" name="email" value="" class="loginField requiredField" placeholder="Email" required />
                    </div>
                    <div class="formFieldWrap">
                        <label class="field-title loginPwdField" for="loginPwdField">Password</label>
                        <input type="password" name="password" value="" class="loginField requiredField" placeholder="Password" required />
                    </div>
                    <div class="formSubmitButtonErrorsWrap">
                        <input type="submit" onclick="javascript:verifyLogin();" class="buttonWrap button-minimal grey-minimal loginSubmitButton" style="width:49%;" value="Login">
                        <a class="button-minimal grey-minimal" style="width:49%;" href="register.php">Register an account</a>
                    </div>
                </div>       
            </div>
        </div>
    </div>
</div>

<script id="alertTemplate" type="text/x-handlebars-template">
    <div class="alert alert-error">
        {{{alert}}}
    </div>
</script>
<?php include('template/footer.php'); ?>