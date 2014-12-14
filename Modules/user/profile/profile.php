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

global $path, $user, $org;

$languages     = get_available_languages();
$roles         = $user -> get_available_roles();
$organisations = $org  -> list_orgnames();
$dates         = $user -> get_available_dateformats();
$times         = $user -> get_available_timeformats();
$separ         = $user -> get_available_separators();



function languagecodetotext()
{
    _('es_ES');
    _('fr_FR');
    _('en_EN');
    _('nl_NL');
    _('nl_BE');
    _('da_DK');
    _('it_IT');
    _('cy_GB');
}
?>
<script type="text/javascript" src="<?php echo $path; ?>Modules/user/profile/profile.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/user/profile/md5.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Modules/user/user.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/listjs/list.js"></script>

<div class="row">

    <div class="col-md-4">
        <h3><?php echo _('My account'); ?></h3>

        <div id="account">

            <span class="text-muted"><?php echo _('Username'); ?></span>
            <span id ="edit-username" style="float:right; display:inline;" class='glyphicon glyphicon-pencil'  title = '<?php echo _('Edit'); ?>'></span>
            <span id ="save-username" style="float:right; display:none;" class='glyphicon glyphicon-floppy-save'  title = '<?php echo _('Edit'); ?>'></span>

            <p>
                <div id="username-view" style="display:inline">
                    <span class="username"></span>
                </div>
                <div id="edit-username-form" style="display:none">
                    <input class="username form-control" type="text" size ="40">
                </div>
            </p>
            <div id="change-username-error" class="alert-danger" style="display:none; width:100%"></div> 


            <span class="text-muted"><?php echo _('Email'); ?></span>
            <span id ="edit-email" style="float:right; display:inline;" class='glyphicon glyphicon-pencil'  title = '<?php echo _('Edit'); ?>'></span>
            <span id ="save-email" style="float:right; display:none;" class='glyphicon glyphicon-floppy-save'  title = '<?php echo _('Edit'); ?>'></span>
            <p>
                <div id="email-view" style="display:inline">
                    <span class="email"></span>
                </div>
                <div id="edit-email-form" style="display:none">
                    <input class="email form-control" type="email" size="40">
                </div>
            </p>
            <div id="change-email-error" class="alert-danger" style="display:none; width:100%"></div> 

            <p>
                <span class="text-muted"><?php echo _('Write API Key'); ?> </span>
                <!--<a id="newapikeywrite" >new</a>-->
                <input type = "text" id="newapikeywrite" class="btn btn-warning key" value ="" >
            </p>
            <p>
                <span class="text-muted"><?php echo _('Read API Key'); ?> </span>
                <!--<a id="newapikeyread" >new</a>-->
                <input type = "text" id="newapikeyread" class="btn btn-warning key" value ="" >
            </p>



            <p>
                <a id="changedetails"><?php echo _('Change Password'); ?></a>
            </p>
            <div id="password-changed" class="alert text-success" style="display:none"><?php echo _('Password is updated!'); ?></div>

        </div>

        <div id="change-password-form" style="display:none">
            <div  class="form-group">
                <label for="oldpassword" class="text-muted"><?php echo _("Current password"); ?></label>
                <input id="oldpassword" type="password" class="form-control" placeholder="'<?php echo _("type Current password"); ?>'"/>
            </div>
            <div  class="form-group">
                <label for="newpassword" class="text-muted"><?php echo _("New password"); ?></label>
                <input id="newpassword" type="password" class="form-control" placeholder="'<?php echo _("type New password"); ?>'"/>
            </div>
            <div  class="form-group">
                <label for="repeatnewpassword" class="text-muted"><?php echo _("Repeat new password"); ?></label>
                <input id="repeatnewpassword" type="password" class="form-control" placeholder="'<?php echo _("retype New password"); ?>'"/>
            </div>
            <div id="change-password-error" class="alert alert-danger" style="display:none;"><?php echo _('Passwords do not match'); ?></div>
            <input id="change-password-submit" type="submit" class="btn btn-primary active" value="<?php echo _('Save'); ?>" />
            <input id="change-password-cancel" type="submit" class="btn" value="<?php echo _('Cancel'); ?>" />
        </div>


    </div>



    <div class="col-md-7">
        <h3><?php echo _('My Profile'); ?></h3>
        <div id="table"></div>
    </div>

</div>

<script>

var path  = "<?php echo $path; ?>";
var lang  = <?php echo json_encode($languages); ?>;
var role  = <?php echo json_encode($roles); ?>;
var orgs  = <?php echo json_encode($organisations); ?>;
var dates = <?php echo json_encode($dates); ?>;
var times = <?php echo json_encode($times); ?>;
var separ = <?php echo json_encode($separ); ?>;

list.data = user.get();

$("#newapikeywrite").val(list.data.apikey_write);
$("#newapikeyread").val(list.data.apikey_read);

// Need to add an are you sure modal before enabling this.
// $("#newapikeyread").click(function(){user.newapikeyread()});
// $("#newapikeywrite").click(function(){user.newapikeywrite()});

var currentlanguage = list.data.language;

list.fields = {
    'gravatar' :{ 'title':"<?php echo _('Gravatar'); ?>", 'type':'gravatar'},
    'name'     :{ 'title':"<?php echo _('Name'); ?>", 'type':'text','tooltip':"<?php echo _('Use letters (a-z) only, accented characters are allowed!')?>"},
    'location' :{ 'title':"<?php echo _('Location'); ?>", 'type':'text','tooltip':"<?php echo _('Use letters (a-z) only, accented characters are allowed!')?>"},
    'timezone' :{ 'title':"<?php echo _('Timezone'); ?>", 'type':'timezone','tooltip':"<?php echo _('Choose your local time offset to UTC')?>"},
    'language' :{ 'title':"<?php echo _('Language'); ?>", 'type':'select','tooltip':"<?php echo _('System available languages')?>", 'options':lang},
    'bio'      :{ 'title':"<?php echo _('Bio'); ?>", 'type':'text'},
    'orgid'    :{ 'title':"<?php echo _('Organisation'); ?>", 'type':'tblselect','tooltip':"<?php echo _('Associate user to an Organisation')?>", 'options':orgs},
    'admin'    :{ 'title':"<?php echo _('User role'); ?>", 'type':'idselect','tooltip':"<?php echo _('Specify the user role')?>", 'options':role},
    'csvparam' :{ 'title':"<?php echo _('CSV separators'); ?>", 'type':'idselect','tooltip':"<?php echo _('Give here your CSV separators preferences, column, decimal, thousands.')?>",'options':separ},
    'csvdate'  :{ 'title':"<?php echo _('CSV date format'); ?>", 'type':'idselect','tooltip':"<?php echo _('Define your prefered CSV date format.')?>",'options':dates},
    'csvtime'  :{ 'title':"<?php echo _('CSV time format'); ?>", 'type':'idselect','tooltip':"<?php echo _('Define your prefered CSV time format.')?>",'options':times},
};
$(startprofile);
list.init();

$("#table").bind("onSave", function(e){
    user.set(list.data);
    // refresh the page if the language has been changed.
    if (list.data.language!=currentlanguage) window.location.href = path+"user/view";
    //window.location.href = path+"user/view";
});

//------------------------------------------------------
// Username
//------------------------------------------------------
$(".username").html(list.data['username']);
$("#input-username").val(list.data['username']);

$("#edit-username").click(function(){
    $("#username-view").hide();
    $("#edit-username-form").show();
    $("#edit-username-form input").val(list.data.username);
});

$("#edit-username-form button").click(function(){

    var username = $("#edit-username-form input").val();

    if (username!=list.data.username)
    {
        $.ajax({
            url: path+"user/changeusername.json",
            data: "&username="+username,
            dataType: 'json',
            success: function(result)
            {
                if (result.success)
                {
                    $("#username-view").show();
                    $("#edit-username-form").hide();
                    list.data.username = username;
                    $(".username").html(list.data.username);
                    $("#change-username-error").hide();
                }
                else
                {
                    $("#change-username-error").html(result.message).show();
                }
            }
        });
    }
    else
    {
        $("#username-view").show();
        $("#edit-username-form").hide();
        $("#change-username-error").hide();
    }
});

//------------------------------------------------------
// Email
//------------------------------------------------------
$(".email").html(list.data['email']);
$("#input-email").val(list.data['email']);

$("#edit-email").click(function(){
    $("#email-view").hide();
    $("#edit-email-form").show();
    $("#edit-email-form input").val(list.data.email);
});

$("#edit-email-form button").click(function(){

    var email = $("#edit-email-form input").val();

    if (email!=list.data.email)
    {
        $.ajax({
            url: path+"user/changeemail.json",
            data: "&email="+email,
            dataType: 'json',
            success: function(result)
            {
                if (result.success)
                {
                    $("#email-view").show();
                    $("#edit-email-form").hide();
                    list.data.email = email;
                    $(".email").html(list.data.email);
                    $("#change-email-error").hide();
                }
                else
                {
                    $("#change-email-error").html(result.message).show();
                }
            }
        });
    }
    else
    {
        $("#email-view").show();
        $("#edit-email-form").hide();
        $("#change-email-error").hide();
    }
});

//------------------------------------------------------
// Password
//------------------------------------------------------
$("#changedetails").click(function(){
    $("#changedetails").hide();
    $("#change-password-form").show();
});

$("#change-password-submit").click(function(){

    var oldpassword = $("#oldpassword").val();
    var newpassword = $("#newpassword").val();
    var repeatnewpassword = $("#repeatnewpassword").val();

    if (newpassword != repeatnewpassword)
    {
        $("#change-password-error").html("<?php echo _('Passwords do not match'); ?>").show();
    }
    else
    {
        $.ajax({
            url: path+"user/changepassword.json",
            data: "old="+oldpassword+"&new="+newpassword,
            dataType: 'json',
            success: function(result)
            {
                if (result.success)
                {
                    $("#oldpassword").val('');
                    $("#newpassword").val('');
                    $("#repeatnewpassword").val('');
                    $("#change-password-error").hide();

                    $("#change-password-form").hide();
                    $("#changedetails").show();
                }
                else
                {
                    $("#change-password-error").html(result.message).show();
                }
            }
        });
    }
});

$("#change-password-cancel").click(function(){
    $("#oldpassword").val('');
    $("#newpassword").val('');
    $("#repeatnewpassword").val('');
    $("#change-password-error").hide();

    $("#change-password-form").hide();
    $("#changedetails").show();
});


</script>
