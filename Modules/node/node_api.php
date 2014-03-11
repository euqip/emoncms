<?php global $path, $session, $user; ?>
<br>
<h2><?php echo _('Node API'); ?></h2>
<h3><?php echo _('Apikey authentication'); ?></h3>
<p><?php echo _('If you want to call any of the following actions when your not logged in, add an apikey to the URL of your request: &apikey=APIKEY.'); ?></p>
<form class="form-horizontal" role="form">
  <div class="form-group">
    <label for="readonlyapi" class="col-sm-4 control-label"><b><?php echo _('Read only:'); ?></b></label>
    <div class="col-sm-4">
      <p type="text" class="form-control" id="readonlyapi"><?php echo $user->get_apikey_read($session['userid']); ?></p>
    </div>
  </div>
  <div class="form-group">
    <label for="writeyapi" class="col-sm-4 control-label"><b><?php echo _('Read & Write:'); ?></b></label>
    <div class="col-sm-4">
      <p type="text" class="form-control" id="writeyapi"><?php echo $user->get_apikey_write($session['userid']); ?><p/>
    </div>
  </div>
</form>
<h3><?php echo _('Posting data'); ?></h3>
<p>The node module accepts a comma seperated string of byte (0-256) values as generated by the rfm12pi adapter board running the RFM12Demo sketch written by Jean Claude Wippler of jeelabs. This byte value string is then decoded by the node module according to the decoder selected into the variables that where packaged up using the struct definitions on the sensor nodes.</p>
<table class="table">
    <tr><td></td><td><a href="<?php echo $path; ?>node/set.json?nodeid=10&data=20,20,20,20"><?php echo $path; ?>node/set.json?nodeid=10&data=20,20,20,20</a></td></tr>
    <tr><td>With write apikey: </td><td><a href="<?php echo $path; ?>node/set.json?nodeid=10&data=20,20,20,20&apikey=<?php echo $user->get_apikey_write($session['userid']); ?>"><?php echo $path; ?>node/set.json?nodeid=10&data=20,20,20,20&<b>apikey=<?php echo $user->get_apikey_write($session['userid']); ?></b></a></td></tr>
    
</table>