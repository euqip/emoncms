<?php
/*
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
*/

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

global $path, $allowusersregister, $enable_rememberme, $enable_password_reset;

?>

<script type="text/javascript" src="<?php echo $path; ?>Modules/user/user.js"></script>

<div style="margin: 0px auto; max-width:392px; padding:10px;">
    <div style="max-width:392px; margin-right:20px; padding-top:45px; padding-bottom:15px; color: #888;">
        <img style="margin:12px;" src="<?php echo $path; ?>Theme/emoncms_logo.png" width="256" height="46" />
    </div>

    <div class="login-container">

        <div id="login-form"  class="well" style="text-align:left">
            <!-- thanks to koppi / iso-country-flags-svg-collection   https://github.com/koppi/iso-country-flags-svg-collection -->
            <?php echo (flagselector($path,__FILE__)); ?>


            <div class ="form-group login-item" tabindex="1">
                <label for="username" class="text-muted"><?php echo _('Username:'); ?></label>
                <input type="text" class="form-control" id="username" placeholder="<?php echo _('Enter your Username'); ?>" />
            </div>

            <div class ="form-group register-item" tabindex="2"  style="display:none">
                <label for="email" class="text-muted"><?php echo _('Email:'); ?></label>
                <input type="email" class="form-control" id="email" placeholder="<?php echo _('Enter your Email address'); ?>" />
            </div>

            <div class ="form-group login-item" tabindex="3">
                <label for="password" class="text-muted"><?php echo _('Password:'); ?></label>
                <input type="password" class="form-control" id="password" placeholder="<?php echo _('Enter your password'); ?>" />
            </div>

            <div class ="form-group register-item" style="display:none" tabindex="4">
                <label for="password-confirm" class="text-muted"><?php echo _('Confirm password:'); ?></label>
                <input type="password" class="form-control" id="password-confirm" placeholder="<?php echo _('Confirm your password'); ?>" />
            </div>


            <div id="error" class="alert-danger" style="display:none;"><?php echo _('Password or user name do not match'); ?></div>

            <p class="login-item">
                <?php if ($enable_rememberme) { ?><label class="checkbox text-muted"><input type="checkbox" tabindex="5" id="rememberme" value="1" name="rememberme"><?php echo '&nbsp;'._('Remember me'); ?></label><br /><?php } ?>
                <button id="login" class="btn btn-primary" tabindex="6" type="button"><?php echo _('Login'); ?></button>
                <?php if ($allowusersregister) { echo '&nbsp;'._('or').'&nbsp' ?><a id="register-link"  href="#"><?php echo _('register'); ?></a><?php } ?>
                <?php echo '&nbsp;'._('or').'&nbsp' ?>
                <a id="passwordreset-link" href="#" ><?php echo _("Forgotten password")?></a>
            </p>

            <p class="register-item" style="display:none">
                <button id="register" class="btn btn-primary" type="button"><?php echo _('Register'); ?></button> <?php echo '&nbsp;'._('or').'&nbsp;' ?>
                <a id="cancel-link" href="#"><?php echo _('cancel'); ?></a>
            </p>

            <div id="passwordreset-block" style="display:none">
                <hr>
                <div id="passwordreset-message"></div>
                <div id="passwordreset-input">
                    <div class ="form-group" tabindex="5">
                        <label for="passwordreset-username" class="text-muted"><?php echo _('Enter account name:'); ?></label>
                        <input type="text" class="form-control" id="passwordreset-username" placeholder="<?php echo _('Enter your account name'); ?>" />
                    </div>
                    <div class ="form-group register-item" tabindex="6">
                        <label for="passwordreset-email" class="text-muted"><?php echo _('Email:'); ?></label>
                        <input type="email" class="form-control" id="passwordreset-email" placeholder="<?php echo _('Enter your Email address'); ?>" />
                    </div>
                    <button id="passwordreset-submit" class="btn btn-primary" tabindex="7" type="button"><?php echo _('Ask new password'); ?></button>
                </div>
            </div>
        </div>

    </div>

</div>
</div>

<script>

var path = "<?php echo $path; ?>";
var register_open = false;
var passwordreset = "<?php echo $enable_password_reset; ?>";

if (!passwordreset) $("#passwordreset-link").hide();

$("#passwordreset-link").click(function(){
    $("#passwordreset-block").show();
    $("#passwordreset-input").show();
    $(".login-item").hide();
    $("#passwordreset-message").html("");
});

$("#passwordreset-submit").click(function(){
    var username = $("#passwordreset-username").val();
    var email = $("#passwordreset-email").val();

    if (email=="" || username=="") {
        alert('<?php echo _("Please enter username and email address") ?>');
    } else {
        var result = user.passwordreset(username,email);
        if (result.success==true) {
            $("#passwordreset-message").html("<div class='alert alert-success'>"+result.message+"</div>");
            $("#passwordreset-input").hide();
        } else {
            $("#passwordreset-message").html("<div class='alert alert-error'>"+result.message+"</div>");
        }
    }
});

$("#register-link").click(function(){
    $(".login-item").hide();
    $(".register-item").show();
    $("#error").hide();
    register_open = true;
    return false;
});

$("#cancel-link").click(function(){
    $(".login-item").show();
    $(".register-item").hide();
    $("#error").hide();
    register_open = false;
    return false;
});

$("input").keypress(function(event) {
//login or register when pressing enter
if (event.which == 13) {
    event.preventDefault();
    if ( register_open ) {
        register();
    } else {
        login();
    }
}
});

function login(){
    var username = $("#username").val();
    var password = $("#password").val();
    var rememberme = 0; if ($("#rememberme").is(":checked")) rememberme = 1;
    var result = user.login(username,password,rememberme);

    if (result.success)
    {
        window.location.href = path+"user/view";
    }
    else
    {
        $("#error").html(result.message).show();
    }
}

function register(){
    var username = $("#username").val();
    var password = $("#password").val();
    var confirmpassword = $("#password-confirm").val();
    var email = $("#email").val();

    if (password != confirmpassword)
    {
        $("#error").show();
    }
    else
    {
        var result = user.register(username,password,email);

        if (result.success)
        {
            var result = user.login(username,password);
            if (result.success)
            {
                window.location.href = path+"user/view";
            }
        }
        else
        {
            $("#error").html(result.message).show();
        }
    }
}

$("#login").click(login);
$("#register").click(register);


</script>
