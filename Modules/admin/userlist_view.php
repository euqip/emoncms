<?php
    global $path, $behavior;
    $usergroupfield="";
?>

<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/table.js"></script>
<h2><?php echo _('Users'); ?></h2>

<div id="table"></div>

<script>

    var path = "<?php echo $path; ?>";
    var groupfield= "<?php echo $behavior['usergoup']; ?>";

    var admin = {
        'userlist':function()
        {
            var result = {};
            $.ajax({ url: path+"admin/userlist.json", dataType: 'json', async: false, success: function(data) {result = data;} });
            return result;
        }
    }

    // Extend table library field types
    //for (z in customtablefields)
    //    table.fieldtypes[z] = customtablefields[z];
    // not necessary, all fields are defined in table.js

    table.element = "#table";

    table.fields = {
        'id':{'title':"<?php echo _('Id'); ?>", 'type':"iconlink",'tooltip':"<?php echo _('Manage user details'); ?>", 'link':"setuser?id=", 'colwidth':" style='width:30px;'"},
        'username':{'title':"<?php echo _('Name'); ?>", 'type':"fixed"},
        'email':{'title':"<?php echo _('Email address'); ?>", 'type':"fixed"},
        'language':{'title':"<?php echo _('Langage'); ?>", 'type':"fixed"},
        'lastlogin':{'title':"<?php echo _('Last login'); ?>", 'type':"fixed"},
    }

    table.groupby = groupfield;
    table.data = admin.userlist();
    table.draw();

</script>
