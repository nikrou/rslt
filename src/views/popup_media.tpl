<html>
  <head>
    <title><?php echo __('Add a media');?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
    <script type="text/javascript" src="index.php?pf=rslt/js/popup.js"></script>
  </head>
  <body>
    <h2><?php echo __('Add a media to an album'); ?></h2>

    <div id="form-media">
      <?php if (!empty($dir['dirs'])):?>
      <ul class="directories">
	<?php foreach ($dir['dirs'] as $directory):?>
	<li>
	  <a href="<?php echo $p_url.'&amp;popup=1&amp;d='.html::sanitizeURL($directory->relname);?>">
	    <img src="<?php echo $directory->media_icon;?>" alt=""/>
	    <?php if ($directory->parent):?>
	    ..
	    <?php else:?>
	    <?php echo text::cutString($directory->basename, 36);?>
	    <?php endif;?>
	  </a>
	</li>
	<?php endforeach;?>
      </ul>
      <?php endif;?>

      <?php if (!empty($dir['files'])):?>
      <ul class="files">
	<?php foreach ($dir['files'] as $file):?>
	<li>
	  <div class="box">
	    <a class="add-media" title="<?php echo __('Attach this file to album');?>" 
	       href="<?php echo $p_url.'&amp;media_id='.$file->media_id.'&amp;album='.$album_id;?>">
	      <img src="images/plus.png" alt="<?php echo __('Attach this file to album');?>"/>
	      <img src="<?php echo $file->media_icon;?>" alt=""/>
	      <?php echo $file->basename;?>
	    </a>
	  </div>
	</li>
	<?php endforeach;?>
      </ul>
      <?php endif;?>
    </div>

    <p><button class="button" id="media-insert-cancel"><?php echo __('Cancel');?></button></p>
  </body>
</html>
