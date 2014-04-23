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
                    <tr>
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

<script>
    var path = "<?php echo $path; ?>";
    var groupfield= "<?php echo $behavior['usergoup']; ?>";
    var expanded= "<?php echo $behavior['userlist_expanded']; ?>";

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

        }
    }

    $("#createuser").click(admin.register);
    $("#expandall").click(function()
    {
        table.groupby = '';
        table.expanded_by_default = true;
        table.draw();
    })
    $("#collapseall").click(function()
    {
        table.groupby = groupfield;
        table.expanded_by_default = false;
        table.draw();
    })

    table.element = "#table";

    table.fields = {
        'id':{'title':"<?php echo _('Id'); ?>", 'type':"iconlink",'tooltip':"<?php echo _('Manage user details'); ?>", 'link':"setuser?id=", 'colwidth':" style='width:30px;'"},
        'username':{'title':"<?php echo _('Name'); ?>", 'type':"fixed"},
        'email':{'title':"<?php echo _('Email address'); ?>", 'type':"fixed"},
        'language':{'title':"<?php echo _('Langage'); ?>", 'type':"fixed"},
        'lastlogin':{'title':"<?php echo _('Last login'); ?>", 'type':"fixed"},
    }

    table.groupby = groupfield;
    table.expanded_by_default = expanded;
    table.data = admin.userlist();
    table.draw();

</script>
