<?php include('template/header.php'); ?>

<script type="text/javascript">
    //This Function register the new user (client side) and eventually login the user in the same time
    function register( login ){
        var data = null;

        var username    = document.getElementsByName( "username" )[0].value;
        var email       = document.getElementsByName( "email" )[0].value;
        var pwd         = document.getElementsByName( "password" )[0].value;
        var age         = document.getElementsByName( "userAge" )[0].value;
        var genre       = document.getElementsByName( "userGenre" )[0].value;
        var lookFor     = document.getElementsByName( "lookFor" )[0].value;
        var description = document.getElementsByName( "userDescription" )[0].value;

          $.ajax({
                type : 'POST',           
                url : server_url + 'application/register', // Servlet URL           
                data:{
                    'username':username,
                    'email':email,
                    'pwd':pwd,
                    'age' : age,
                    'genre' : genre,
                    'lookFor' : lookFor,
                    'description' : description,
                },
                success : function( data ) {        
                    if( data.success ){
                        alert( 'Registration Successfull !' );

                        if ( login ) {
                            verifyLogin();
                        }
                        else
                        {
                            //Redirect
                            window.location.href = "index.php";
                        }

                    }
                    else
                    {
                        if( data.errors ) {
                            //define
                            var error = {};
                            error.alert = data.errors;

                            //Append
                            var template = Handlebars.compile( $('#alertTemplate').html() );
                            $('#errors').empty().append( template(error) );

                            //Erase
                            error = {};
                            data.error = false;
                        }else{
                            alert( 'Registration Error' );
                        }

                        //Redirect
                        // window.location.href="index.php";
                    }
                },
                error : function(xhr, type) {
                    alert( 'server error occurred' );
                } 
          });    
    }
    // TO BE DELETED WHEN MERGED FILES SECTION WITH JQUERY MOBILE, REGISTER AND INDEX SHOULD BE IN THE SAME FILE SO THE FUNCTIONS WILL BE TOGETHER!!
    function verifyLogin(){
        var data = null;

        var email=document.getElementsByName("email")[0].value;
        var pwd=document.getElementsByName("password")[0].value;  

        $.ajax({
            type : 'POST',           
            url : server_url + 'application/login', // Servlet URL           
            data:{
                'email':email,
                'pwd':pwd
            },
            success : function(data) {
                if(data.logged_in){
                    //We set up the Secuity Key
                        //FOR BROWSER TESTING ONLY !!!!!!!!!!!!!
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
    <div class="content"><br/><br/>
        <div class="container no-bottom">
            <div class="contact-form no-bottom">
                <div id="errors">
                    <!-- Handlebars -->
                </div>
                <div class="formFieldWrap">
                    <label class="field-title registerUserField" for="registerUserField">Username</label>
                    <input type="text" name="username" value="" class="registerField requiredField"  placeholder="Username" required />
                </div>
                <div class="formFieldWrap">
                    <label class="field-title registerEmailField" for="registerEmailField">Email</label>
                    <input type="email" name="email" value="" class="registerField requiredField"  placeholder="Email" required />
                </div>
                <div class="formFieldWrap">
                    <label class="field-title registerPwdField" for="registerPwdField">Password</label>
                    <input type="password" name="password" value="" class="registerField requiredField" placeholder="8 chars minimum" />
                </div>
                <div class="formFieldWrap">
                    <label class="field-title addfriendNameField" for="addfriendEmailField">Age</label>
                    <input type="number" name="userAge" value="" class="addfriendField requiredField"  />
                </div>
                <div class="formFieldWrap">
                    <label class="field-title addfriendNameField" for="addfriendEmailField">Genre</label>                        
                    <select name="userGenre" id="toggleswitch1" class="addfriendField requiredField" data-theme="" data-role="slider">
                        <option value="M">
                            Man
                        </option>
                        <option value="F">
                            Woman
                        </option>
                    </select>
                </div>
                <div class="formFieldWrap">
                    <label class="field-title addfriendNameField" for="addfriendEmailField">You're Looking For</label>                        
                    <select name="lookFor" id="toggleswitch2" data-theme="" data-role="slider" class="addfriendField requiredField">
                        <option value="M">
                            Man
                        </option>
                        <option value="F">
                            Woman
                        </option>
                    </select>
                </div>
                <div class="formFieldWrap">
                    <label class="field-title addfriendNameField" for="addfriendNameField">Descritpion</label>
                    <textarea name="userDescription" value="" class="addfriendField requiredField"></textarea>
                </div>
                <div class="formSubmitButtonErrorsWrap">
                    <input type="submit"  onclick="javascript:register(0);" class="buttonWrap button-minimal grey-minimal registerSubmitButton" style="width:49%;" id="registerSubmitButton" value="Register">
                    <input type="submit"  onclick="javascript:register(1);" class="buttonWrap button-minimal grey-minimal registerSubmitButton" style="width:49%;" id="loginSubmitButton" value="Register & Login">
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