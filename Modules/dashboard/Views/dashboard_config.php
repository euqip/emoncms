<?php
    /*
    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
    */

    global $session,$path;

?>

<div class="modal fade emoncms-dialog type-primary" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo _('Dashboard configuration'); ?></h4>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="form-group">
                        <label for="dash_name" ><?php echo _('Dashboard name').' :'; ?></label>
                        <input type="email" class="form-control" id="dash_name" placeholder="<?php echo _('Name'); ?>"  value="<?php echo $dashboard['name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="dash_alias" ><?php echo _('Menu name (lowercase a-z only)').' :'; ?></label>
                        <input type="email" class="form-control" id="dash_alias" placeholder="<?php echo _('Name'); ?>"  value="<?php echo $dashboard['alias']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="dash_description" ><?php echo _('Description').' :'; ?></label>
                        <input type="email" class="form-control" id="dash_description" placeholder="<?php echo _('Name'); ?>"  value="<?php echo $dashboard['description']; ?>">
                    </div>

                    <div class="checkbox">
                      <label title="<?php echo _('Make this dashboard the first shown'); ?>"><?php echo _('Main'); ?>
                        <input type="checkbox" id="chk_main" 
                        value= "1 "<?php if ($dashboard["main"] == true) echo 'checked'; ?>
                        />
                      </label>
                    </div>


                    <div class="checkbox">
                      <label title="<?php echo _('Activate this dashboard'); ?>"><?php echo _('Published'); ?>
                        <input type="checkbox" id="chk_published" 
                        value="1 " <?php if ($dashboard["published"] == true) echo 'checked'; ?>
                        />
                      </label>
                    </div>

                    <div class="checkbox">
                      <label title="<?php echo _('Anyone with the URL can see this dashboard'); ?>"><?php echo _('Public'); ?>
                        <input type="checkbox" id="chk_public" 
                        value="1 " <?php if ($dashboard["public"] == true) echo 'checked'; ?>
                        />
                      </label>
                    </div>

                    <div class="checkbox">
                      <label title="<?php echo _('Shows dashboard description on mouse over dashboard name in menu project'); ?>"><?php echo _('Description'); ?>
                        <input type="checkbox" id="chk_showdescription" 
                        value="1 "<?php if ($dashboard["showdescription"] == true) echo 'checked'; ?>
                        />
                      </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" id="configure-save"><span class="emoncms-dialog-button-icon glyphicon glyphicon-save"></span><?php echo _('Save Changes'); ?></button>
                <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo _('Cancel'); ?></button>
             </div>
        </div>
    </div>
</div>




<script type="application/javascript">

    var dashid = <?php echo $dashboard['id']; ?>;
    var path = "<?php echo $path; ?>";

    $("#configure-save").click(function (e)
    {
        e.preventDefault();
        var fields = {};
        console.log ("dash id = "+ dashid);


        fields['name'] = $("#dash_name").val();
        fields['alias']  = $("#dash_alias").val();
        fields['description']  = $("#dash_description").val();



        if ($("#chk_main").is(":checked")) fields['main'] = true; else fields['main'] = false;
            if ($("#chk_public").is(":checked")) fields['public'] = true; else fields['public'] = false;
        if ($("#chk_published").is(":checked")) fields['published'] = true; else fields['published'] = false;
            if ($("#chk_showdescription").is(":checked")) fields['showdescription'] = true; else fields['showdescription'] = false;

        console.log ("dash id = "+ dashid+ " alias="+fields['alias']+ " main="+fields['main']+ " public="+fields['public']);

        $.ajax({
            url :  path+"dashboard/set.json",
            data : "&id="+dashid+"&fields="+JSON.stringify(fields),
            dataType : 'json',
            success : function(result) {console.log(result)}
        });

        $('#myModal').modal('hide');
    });
</script>

