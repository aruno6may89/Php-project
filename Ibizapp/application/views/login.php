<?php include 'header.php';?>
<script type="text/javascript" src='<?=JS_DIR?>jquery.validate.min.js'></script>
<script type="text/javascript">
$(document).ready(function(){
            $("#login").validate({
                debug:false,
                errorElement:'span',
                rules:
                {
                    username:  {
                                required: true
                            },
                    password:  {
                                required: true
                            }
                },
                messages:
                {
                    username:  {
                                required: 'Enter User Name'
                            },
                    password:  {
                                required: 'Enter Your Password'
                            }
                },
                errorPlacement: function(error, element) {
                        if(element.attr('id') === "username") {
                            error.appendTo(".username");
                            //error.insertAfter(".chosen-container");
                        } else if(element.attr('id') === "password") {
                            error.appendTo(".password");
                            //error.insertAfter(".chosen-container");
                        }
//                        else {
//                            error.appendTo(".errors");
//                        }
                    }
        });
});
</script>
        <!-- App main html start -->
        <div class="login container">
        	<div class="login-content">
            	<div class="center">
                	<img src="<?=IMG_DIR?>/i-Bizsuite-Logo.png" width="153" height="36" alt="i-BizSuite" title="Welcome to i-Bizsuite">
                </div>
            	<div class="login-form">
                	<h3>Sign in to your Account</h3>
                    <form name="login" action="<?=BASE_URL?>/login/login_validation" method="post" id="login">
                    	<fieldset>
                        	<div class="control-group">
                                <?php echo validation_errors();?>
                                <label for="email">Email or Username</label>
                                <input class="input-block-level" name="username" id="username" type="username">
                                 <span class="b-error username"></span>
                            </div>
                        	<div class="control-group">
                                <label for="password">Password <a href="#" class="pull-right">Forgot Password  ?</a></label>
                                <input class="input-block-level" name="password" id="password" type="password">
                                <span class="b-error password"></span>
                            </div>
                            <div class="clearfix">
                            	<label for="remember" class="checkbox pull-left">
                                    <input id="remember" name="remember" type="checkbox"> Keep me Signed In
                                </label>
                                <input type="submit" class="cus-buttons cus-buttons-medium pull-right" value="Sign in">
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
<?php include 'footer.php'; ?>       