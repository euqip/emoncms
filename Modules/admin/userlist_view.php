<?php
global $path, $behavior;
$usergroupfield="";
?>

<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/table.js"></script>
<h2><?php echo _('Users'); ?>
    <a href='#modal-create' data-toggle="modal" id="adduser">
        <small>
            <span class = "glyphicon glyphicon-plus-sign" title = '<?php echo _("Add new user")?>'></span>
        </small>
    </a>
    <a href='#'  id="expandall">
        <small>
            <span class = "glyphicon glyphicon-expand" title = '<?php echo _("Expand all")?>'></span>
        </small>
    </a>
    <a href='#'  id="collapseall">
        <small>
            <span class = "glyphicon glyphicon-collapse-up" title = '<?php echo _("Collapse all")?>'></span>
        </small>
    </a>
    <span class="alert-danger pull-right fade" id ="msgfeeback"></span>
</h2>

<div id="table"></div>


<div class="modal fade  emoncms-dialog type-success" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo _('New user creation') ?></h4>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <td class="option_name"><?php echo _('User name') ?></td>
                        <td><input class="form-control options" id="newusername" type="text" value=""></td>
                        <td><small><p class="muted"><?php echo _('The user name must be unique') ?></p></small></td>
                    </tr>
                    <tr>
                        <td class="option_name"><?php echo _('User Email') ?></td>
                        <td><input class="form-control options" id="newemail" type="text" value=""></td>
                        <td><small><p class="muted"><?php echo _('User Email, should be unique!') ?></p></small></td>
                    </tr>
                    so<tr>
                    <td class="option_name"><?php echo _('Password') ?></td>
                    <td><input class="form-control options" id="newpassword" type="text" value="<?php echo substr(hash('sha256', 'password'),1,8); ?>"></td>
                    <td><small><p class="muted"><?php echo _('The password should be changed at first next login') ?></p></small></td>
                </tr>
                <tr>
                    <td colspan="3"><small><p class="muted"><?php echo _('These are the main user data, all the remaininng user parameters are updatable later') ?></p></small></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" id = "createuser" class="btn btn-primary"><?php echo _('Create') ?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Cancel') ?></button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<button class="btn btn-primary" data-toggle="modal" data-target="#alertmsg">Feedback message</button>

<div class="modal fade " tabindex="-1" id="alertmsg" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm emoncms-dialog"  id="alertmessage">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id= "feedbacktitle"></h4>
        </div>
        <div class="modal-body" id="feedbackmessage"></div>
    </div>
</div>
</div>

<script>
    var path = "<?php echo $path; ?>";
    var groupfield= "<?php echo $behavior['usergoup']; ?>";
    var expanded= "<?php echo $behavior['userlist_expanded']; ?>";
    var success= "<?php echo _('Success'); ?>";
    var error= "<?php echo _('Error'); ?>";





    var admin = {
        'userlist':function()
        {
            var result = {};
            $.ajax({ url: path+"admin/userlist.json", dataType: 'json', async: false, success: function(data) {result = data;} });
            return result;
        },
        'register':function()
        {
            var result = {};
            var username = $("#newusername").val();
            var email = $("#newemail").val();
            var password = $("#newpassword").val();
            $.ajax({
                type: "POST",
                url: path+"user/register.json",
                data: "&username="+username+"&password="+encodeURIComponent(password)+"&email="+email,
                dataType: 'json',
                async: false,
                success: function(data)
                {
                    result = data;
                }
            })
            if (result.success){
                var result = admin.forcepwd(result.userid);
            } else {
                $("#error").html(result.message).show();
            }
        },
        'forcepwd':function(userid)
        {
            var result = {};
            $.ajax({
                type: "POST",
                url: path+"user/forcepwdchange.json",
                data: "&userid="+userid,
                dataType: 'json',
                async: false,
                success: function(data)
                {
                    result = data;
                }
            })
            if (result.success){
                //window.location.href = path+"user/view?id="+result.userid;
                window.location.href = "setuser?id="+result.userid;
            } else {
                $("#error").html(result.message).show();
            }
        },
        'passwordreset':function(username, usermail)
        {
            var result = {};
            $.ajax({
                type: "GET",
                url: path+"user/passwordreset.json",
                data: "&username="+username+"&email="+usermail,
                dataType: 'json',
                async: false,
                success: function(data)
                {
                    result = data;
                    if (result.success){
                        $('#feedbacktitle').html(success);
                        var boxclass="alert-success";
                    } else {
                        $('#feedbacktitle').html(error);
                        var boxclass="alert-error";
                    }
                    //$('#feedbacktitle').html("<div class='alert "+boxclass+"'>"+result.message+"</div>");
                    $('#feedbackmessage').html(result.message);
                    $('#alertmessage').addClass(boxclass);
                    $('#alertmessage .modal-header').addClass(boxclass);
                    $('#alertmsg').modal('show');
                    setTimeout(function(){
                      $('#alertmsg').modal('hide')
                    }, 1000);
                }
            })
            if (!result.success){
                $("#error").html(result.message).show();
            }

        }

    }

    $("#createuser").click(admin.register);
    $("#expandall").click(function()
    {
        table.groupby = '';
        update(true);
    })
    $("#collapseall").click(function()
    {
        table.groupby = groupfield;
        update(false);
    })

    table.element = "#table";

    table.fields = {
        'id':{'title':"<?php echo _('Id'); ?>", 'type':"iconlink",'tooltip':"<?php echo _('Manage user details'); ?>", 'link':"setuser?id=", 'colwidth':" style='width:30px;'"},
        'pwd':{'title':"<?php echo _('Pwd Reset'); ?>", 'type':"iconbasic", 'icon':'glyphicon glyphicon-send','tooltip':"<?php echo _('Reset password and send new one'); ?>", 'icon_action':"passwordreset", 'colwidth':" style='width:30px;'"},
        'username':{'title':"<?php echo _('Name'); ?>", 'type':"fixed"},
        'email':{'title':"<?php echo _('Email address'); ?>", 'type':"fixed"},
        'language':{'title':"<?php echo _('Langage'); ?>", 'type':"fixed"},
        'lastlogin':{'title':"<?php echo _('Last login'); ?>", 'type':"fixed"},
    }

    table.groupby = groupfield;
    update(expanded);

    function update(how){
        table.expanded_by_default = how;
        table.data = admin.userlist();

        table.draw();
        //check if no data are present to show how to proceed.
        if(table.expanded_by_default){
            $("#collapseall").show();
            $("#expandall").hide();
        } else {
            $("#collapseall").hide();
            $("#expandall").show();
        }
    }

    function module_event(evt, elt, row, uid, action){
        console.log('Userlist module row= '+row+' - field= '+field+' - uid= '+uid+' - iconaction= '+action);
        switch(action)
        {
            case "passwordreset":
            var uname= table.data[row].username;
            var mail = table.data[row].email;
            admin.passwordreset(uname, mail);
            break;

            default:
            //each unknown action is traznsfered to the module code
            //module_event(e,$(this),row,uid,action);
        }
    }

</script>
