<?php
    global $path, $behavior;
    $languages = get_available_languages();
    //todo: a detailed org view to maintain address and details.
    //This view will be provided to the Org admin.

?>

<script type="text/javascript" src="<?php echo $path; ?>Lib/tablejs/table.js"></script>

<div class="container">
    <div id="localheading">
        <h2><?php echo _('Organisations'); ?>
            <small>
                <a href='#modal-create' data-toggle="modal" id="adddorganisation">
                    <span class = "glyphicon glyphicon-paperclip" title = "<?php echo _("Add new organisation")?>"></span>
                </a>
                <a href='#'  id="expandall">
                    <span class = "glyphicon glyphicon-plus-sign" title = '<?php echo _("Expand all")?> '></span>
                </a>
                <a href='#'  id="collapseall">
                    <span class = "glyphicon glyphicon-minus-sign" title = '<?php echo _("Collapse all")?>'></span>
                </a>
                <a href='#'  id="nogroups">
                    <span class = "glyphicon glyphicon glyphicon-list-alt" title = '<?php echo _("Hide groups")?>'></span>
                </a>
                <span class="alert-danger pull-right fade" id ="msgfeeback"></span>
            </small>
        </h2>
    </div>

    <div id="table"></div>

    <div id="organisations"  style ="display:none;">
        <p class = "alert alert-danger">
            <?php
            echo _('There is no organisation yet defined in your system, create at least one.  ');
            echo _('You will be afterward able to create many users (at least one) linked to this organisation. ');
            echo _('Click the  ') ;?>
            <a href='##modal-create' data-toggle="modal" id="adddorganisation">
                   <span class = "glyphicon glyphicon-plus-sign" title = '<?php echo _("Add new organisation")?>'></span>
            </a>
            <?php echo _(' icon here or above to create the first one.') ;?>

        </p>

    </div>

    <div class="modal fade  emoncms-dialog type-success" id="modal-create">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo _('New organisation creation') ?></h4>
                </div>
                <div class="modal-body">
                    <table>
                        <tr>
                            <td class="option_name"><?php echo _('Short name') ?></td>
                            <td><input class="form-control options" id="neworgname" type="text" value=""></td>
                            <td><small><p class="muted"><?php echo _('The short name must be unique') ?></p></small></td>
                        </tr>
                        <tr>
                            <td class="option_name"><?php echo _('Organisation name') ?></td>
                            <td><input class="form-control options" id="newlongname" type="text" value=""></td>
                            <td><small><p class="muted"><?php echo _('New organisation long name') ?></p></small></td>
                        </tr>
                        <tr>
                            <td colspan="3"><small><p class="muted"><?php echo _('These are the main organisation data, all the remaininng parameters are updatable later') ?></p></small></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" id = "createorg" class="btn btn-primary"><?php echo _('Create') ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Cancel') ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade emoncms-dialog type-danger" id="modal-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo _('Confirm organisation deletion') ?></h4>
                </div>
                <div class="modal-body">
                    <p>
                        <?php echo _('Are you sure you want to delete this organisation, the oparation is non reversible!') ?>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmdelete"><span class="button-icon glyphicon glyphicon-trash"></span><?php echo _('Confirm delete') ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Cancel') ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>

<script>

    var path = "<?php echo $path; ?>";
    var lang = <?php echo json_encode($languages); ?>;
    var groupfield= "<?php echo $behavior['orggroup']; ?>";
    var expanded= "<?php echo $behavior['orglist_expanded']; ?>";
    var firstrun   = true;
    var success    = "<?php echo _('Success'); ?>";
    var error      = "<?php echo _('Error'); ?>";

    var admin = {
        'orglist':function()
        {
            var result = {};
            $.ajax({ url: path+"admin/orglist.json", dataType: 'json', async: false, success: function(data) {result = data;} });
            return result;
        },
        'createorg':function(fields)
        {
            var result = {};
            //console.log(fields);
            $.ajax({
                url: path+"admin/org/create.json",
                type : 'POST',
                dataType : 'json',
                data:'create=0&orgfields={"orgname":"'+fields.orgname+'","longname":"'+fields.longname+'"}' ,
                async: false,
                success: function(data){
                    if (data['success'] == false){
                        $('#msgfeeback').html(data.message);
                        $("#msgfeeback").delay(200).addClass("in").fadeOut(4000);
                    }
                }
                  });
            return result;
        },
        'delorg':function(id)
        {
            var result = {};
            $.ajax({ url: path+"admin/org/delete.json", data: "id="+id, async: false, success: function(data){} });
            return result;
        },
        'update':function(id, fields)
        {
            var result = {};
            $.ajax({ url: path+"admin/org/update.json", data: "orgid="+id+"&fields="+JSON.stringify(fields), async: false, success: function(data){} });
            return result;
        },

    }

    $("#expandall").click(function()
    {
        table.groupby = groupfield;
        table.expand = true;
        table.tablegrpidshow = false;
        table.state = 1;
        update();
    })
    $("#collapseall").click(function()
    {
        table.groupby = groupfield;
        table.collapse = true
        table.tablegrpidshow = false;
        table.state = 0;
        update();
    })
    $("#nogroups").click(function()
    {
        table.groupby = '';
        table.expand = true;
        table.tablegrpidshow = true;
        table.state = 2;
        update();
    })

    table.element = "#table";

    table.fields = {
        'delete-action':{'title':'','tooltip':"<?php echo _('Suppress organisation'); ?>", 'type':"delete", 'display':"yes", 'colwidth':" style='width:30px;'"},
        'edit-action':{'title':'','tooltip':"<?php echo _('Edit organisation attributes'); ?>",'alt':'<?php echo _("Save"); ?>', 'type':"edit", 'display':"yes", 'colwidth':" style='width:30px;'"},

        'id':{'title':"<?php echo _('Id'); ?>", 'type':"iconlink",'tooltip':"<?php echo _('Manage organisation details'); ?>", 'link':"setorg?id=", 'colwidth':" style='width:30px;'"},
        'orgname':{'title':"<?php echo _('short Name'); ?>",'tooltip':"<?php echo _('Should be unique'); ?>", 'type':"fixed", 'colwidth':" style='width:100px;'"},
        'country':{'title':"<?php echo _('Country'); ?>", 'type':"text", 'colwidth':" style='width:200px;'"},
        'timezone':{'title':"<?php echo _('Time zone'); ?>",'tooltip':"<?php echo _('Default time zone for organisation users'); ?>", 'type':"tzone", 'colwidth':" style='width:200px;'"},
        'longname':{'title':"<?php echo _('Company Name'); ?>",'tooltip':"<?php echo _('Organisation long name'); ?>", 'type':"text", 'colwidth':" style='width:300px;'"},
        'language':{'title':"<?php echo _('Language'); ?>",'tooltip':"<?php echo _('Default language for organisation users'); ?>", 'type':'select', 'options':lang},
        'lastuse':{'title':"<?php echo _('Last use'); ?>", 'type':"fixed"}
    }

    table.groupby = groupfield;
    update(expanded);

    function update(how){
        table.expanded_by_default = how;
        table.data = admin.orglist();
        if (firstrun) {
            table.expand=expanded;
            table.collapse=!expanded;
            table.state=expanded;
        }
        table.draw();
        if (table.data.length != 0) {
            $("#organisations").hide();
            if(table.expanded_by_default){
                $("#collapseall").show();
                $("#expandall").hide();
            } else {
                $("#collapseall").hide();
                $("#expandall").show();
            }
        } else {
            $("#organisations").show();
            $("#collapseall").hide();
            $("#expandall").hide();
        };
        if(table.state){
            $("#collapseall").show();
            $("#expandall").hide();
        } else {
            $("#collapseall").hide();
            $("#expandall").show();
        }
        firstrun = false;


    }


$("#table").bind("onEdit", function(e){});

$("#table").bind("onSave", function(e,id,fields_to_update){
    admin.update(id,fields_to_update);
});

$("#table").bind("onDelete", function(e,id,row){
    $('#modal-delete').modal('show');
    $('#modal-delete').attr('orgid',id);
    $('#modal-delete').attr('orgrow',row);
})

$("#confirmdelete").click (function(e){
    $('#modal-delete').modal('hide');
    admin.delorg($('#modal-delete').attr('orgid'));
    update();

})

$("#createorg").click(function(){
    var data = {
        orgname : $('#neworgname').val(),
        longname :$('#newlongname').val()
    };
    admin.createorg(data);
    $('#modal-create').modal('hide');
    update();

});

</script>
