<?php
    global $path, $behavior, $credits;
?>

<div class="container">
    <div id="localheading">
        <h2><?php echo _('Emoncms Credits'); ?>
            <small>
                <a href="api">
                    <span class = "glyphicon glyphicon-info-sign" title = "<?php echo _('Credits Help'); ?>"></span>
                </a>
            </small>
        </h2>
    </div>
    <div class="container">
    	<div class="row border">
			<div class="col-md-3 logo"><img src="http://php.net/images/logo.php" alt="PHP logo" align="middle"></div>
			<div class="col-md-9"><a href="http://php.net/"  target="_blank">
			<?php echo _("PHP is a popular general-purpose scripting language that is especially suited to web development.
				Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world."); ?></a></div>
		</div>
    	<div class="row border">
			<div class="col-md-3 logo"><img src="<?php echo $path; ?>Theme/credits/jquery.png" alt="jquery version 1.11 logo" align="middle"></div>
			<div class="col-md-9"><a href="http://jquery.com/"  target="_blank">
			<?php echo _("jQuery is a fast, small, and feature-rich JavaScript library. It makes things like HTML document traversal and manipulation, event handling, animation, and Ajax much simpler with an easy-to-use API that works across a multitude of browsers. With a combination of versatility and extensibility, jQuery has changed the way that millions of people write JavaScript."); ?></a></div>
		</div>
    	<div class="row border">
			<div class="col-md-3 logo"><img src="<?php echo $path; ?>Theme/credits/bootstrap.jpg" alt="Twitter bootstrap version 3" align="middle"></div>
			<div class="col-md-9"><a href="http://getbootstrap.com/"  target="_blank">
				<?php echo _("Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web."); ?>
			</a></div>
		</div>
    	<div class="row border">
			<div class="col-md-3 logo"><img src="<?php echo $path; ?>Theme/credits/glypicons-logo.svg" alt="Glyphicons are included in bootstrap 3" align="middle"></div>
			<div class="col-md-9"><a href="http://glyphicons.com/"  target="_blank">
				<?php echo _("GLYPHICONS is a library of precisely prepared monochromatic icons and symbols, created with an emphasis on simplicity and easy orientation."); ?>
			</a></div>
		</div>
    	<div class="row border">
			<div class="col-md-3 logo"><img src="<?php echo $path; ?>Theme/credits/redis.png" alt="Redis" align="middle"></div>
			<div class="col-md-9"><a href="http://redis.io/"  target="_blank">
				<?php echo _("Redis is an open source, BSD licensed, advanced key-value cache and store. It is often referred to as a data structure server since keys can contain strings, hashes, lists, sets, sorted sets, bitmaps and hyperloglogs."); ?>
			</a></div>
		</div>
    	<div class="row border">
			<div class="col-md-3 logo"><img src="<?php echo $path; ?>Theme/credits/phpmailer_logo.jpg" alt="PHP Mailer logo" align="middle"></div>
			<div class="col-md-9"><a href="http://phpmailer.worxware.com/"  target="_blank">
				<?php echo _("PHPMailer continues to be the world's most popular transport class, with an estimated 9 million users worldwide. Downloads continue at a significant pace daily.");

				echo _('The current "official" version of PHPMailer is available through Github: https://github.com/Synchro/PHPMailer.'); ?>
			</a></div>
		</div>


	    <?php
	    $credits=load_credits();
	    foreach ($credits as $items){
	    	foreach ($items as $item){
		    	echo '<div class="row border">';
		    		if($item['logopath']=='') $item['logopath']= 'Theme/emoncms_logo.png';
					echo '<div class="col-md-3 logo"><img src="'.$item['logopath'].'" alt="'.$item['logoalt'].'"></div>';
					echo '<div class="col-md-9"><a href="'.$item['targetpath'].'"  target="_blank">';
						echo '<h4>'.$item['module'].'</h4>';
						echo $item['text'];
					echo '</a></div>';
				echo '</div>';
			}
	    }
	    ?>

    </div>
</div>