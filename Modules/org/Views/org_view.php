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
$modulename = "org";

    $languages = get_available_languages();
    $languages_name = languagecode_to_name($languages);
    //languages order by language name
    $languages_new = array();
    foreach ($languages_name as $key=>$lang){
       $languages_new[$key]=$languages[$key];
    }
    $languages= array_values($languages_new);
    $languages_name= array_values($languages_name);


function languagecode_to_name($lang){

    foreach ($lang as $key=>$val){
      //echo $key.'-'.$val;
      switch($val) {
              case 'cy_GB': $lang[$key]=_('Welsh (United Kingdom)'); break;
              case 'da_DK': $lang[$key]=_('Danish (Denmark)'); break;
              case 'en_EN': $lang[$key]=_('English'); break;
              case 'es_ES': $lang[$key]=_('Spanish (Spain)'); break;
              case 'fr_FR': $lang[$key]=_('French (France)'); break;
              case 'it_IT': $lang[$key]=_('Italian (Italy)'); break;
              case 'nl_BE': $lang[$key]=_('Dutch (Belgium)'); break;
              case 'nl_NL': $lang[$key]=_('Dutch (Netherlands)'); break;
              case 'de_DE': $lang[$key]=_('German (Germany)'); break;
      }
    }
   asort($lang);
   return $lang;
}
?>
<script type="text/javascript" src="<?php echo $path.MODULE; ?>/org/Views/org_profile.js"></script>
<script type="text/javascript" src="<?php echo $path.MODULE; ?>/user/profile/md5.js"></script>
<script type="text/javascript" src="<?php echo $path.MODULE; ?>/org/org.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Lib/listjs/list.js"></script>

<div class="row">

    <div class="col-md-4">
        <h3><?php echo _('My organisation'); ?></h3>

        <div id="account">

            <span class="text-muted"><?php echo _('orgname'); ?></span>
            <span id ="edit-orgname" style="float:right; display:inline;" class='glyphicon glyphicon-pencil'  title = '<?php echo _('Edit'); ?>'></span>
            <span id ="save-orgname" style="float:right; display:none;" class='glyphicon glyphicon-floppy-save'  title = '<?php echo _('Edit'); ?>'></span>

            <p>
                <div id="orgname-view" style="display:inline">
                    <span class="orgname"></span>
                </div>
                <div id="edit-orgname-form" style="display:none">
                    <input class="orgname form-control" type="text" size ="40">
                </div>
            </p>
            <div id="change-orgname-error" class="alert-danger" style="display:none; width:100%"></div>


            <span class="text-muted"><?php echo _('Main Email'); ?></span>
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


        </div>

    </div>



    <div class="col-md-7">
        <h3><?php echo _('Organisation Profile'); ?></h3>
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

list.data = org.get();

$("#newapikeywrite").val(list.data.apikey_write);
$("#newapikeyread").val(list.data.apikey_read);

// Need to add an are you sure modal before enabling this.
// $("#newapikeyread").click(function(){org.newapikeyread()});
// $("#newapikeywrite").click(function(){org.newapikeywrite()});

var currentlanguage = list.data.language;

list.fields = {
    'logo'     :{ 'title':"<?php echo _('Logo'); ?>", 'type':'gravatar'},
    'longname' :{ 'title':"<?php echo _('Full Name'); ?>", 'type':'text','tooltip':"<?php echo _('Use letters (a-z) only, accented characters are allowed!')?>"},
    'street'   :{ 'title':"<?php echo _('Street'); ?>", 'type':'text','tooltip':"<?php echo _('Use letters (a-z) only, accented characters are allowed!')?>"},
    'zip'      :{ 'title':"<?php echo _('Zip'); ?>", 'type':'text','tooltip':"<?php echo _('Use letters (a-z) only, accented characters are allowed!')?>"},
    'city'     :{ 'title':"<?php echo _('City name'); ?>", 'type':'text','tooltip':"<?php echo _('Use letters (a-z) only, accented characters are allowed!')?>"},
    'state'    :{ 'title':"<?php echo _('State name'); ?>", 'type':'text','tooltip':"<?php echo _('Use letters (a-z) only, accented characters are allowed!')?>"},
    'country'  :{ 'title':"<?php echo _('Country'); ?>", 'type':'text','tooltip':"<?php echo _('Use letters (a-z) only, accented characters are allowed!')?>"},
    'location' :{ 'title':"<?php echo _('Location'); ?>", 'type':'text','tooltip':"<?php echo _('Geographical location lat,long')?>"},
    'timezone' :{ 'title':"<?php echo _('Timezone'); ?>", 'type':'timezone','tooltip':"<?php echo _('Choose your local time offset to UTC')?>"},
    'language' :{ 'title':"<?php echo _('Language'); ?>", 'type':'select','tooltip':"<?php echo _('System available languages')?>", 'options':lang},
    'csvparam' :{ 'title':"<?php echo _('CSV separators'); ?>", 'type':'idselect','tooltip':"<?php echo _('Give here your CSV separators preferences, column, decimal, thousands.')?>",'options':separ},
    'csvdate'  :{ 'title':"<?php echo _('CSV date format'); ?>", 'type':'idselect','tooltip':"<?php echo _('Define your prefered CSV date format.')?>",'options':dates},
    'csvtime'  :{ 'title':"<?php echo _('CSV time format'); ?>", 'type':'idselect','tooltip':"<?php echo _('Define your prefered CSV time format.')?>",'options':times},
};
$(startprofile);
list.init();

$("#table").bind("onSave", function(e){
    org.set(list.data);
    // refresh the page if the language has been changed.
    if (list.data.language!=currentlanguage) window.location.href = path+"org/view";
    //window.location.href = path+"user/view";
});

//------------------------------------------------------
// orgname
//------------------------------------------------------
$(".orgname").html(list.data['orgname']);
$("#input-orgname").val(list.data['orgname']);

$("#edit-orgname").click(function(){
    $("#orgname-view").hide();
    $("#edit-orgname-form").show();
    $("#edit-orgname-form input").val(list.data.orgname);
});

$("#edit-orgname-form button").click(function(){

    var orgname = $("#edit-orgname-form input").val();

    if (orgname!=list.data.orgname)
    {
        $.ajax({
            url: path+"user/changeorgname.json",
            data: "&orgname="+orgname,
            dataType: 'json',
            success: function(result)
            {
                if (result.success)
                {
                    $("#orgname-view").show();
                    $("#edit-orgname-form").hide();
                    list.data.orgname = orgname;
                    $(".orgname").html(list.data.orgname);
                    $("#change-orgname-error").hide();
                }
                else
                {
                    $("#change-orgname-error").html(result.message).show();
                }
            }
        });
    }
    else
    {
        $("#orgname-view").show();
        $("#edit-orgname-form").hide();
        $("#change-orgname-error").hide();
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


</script>
