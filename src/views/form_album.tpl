<html>
  <head>
    <title><?php echo $page_title.' - '.__('Albums'); ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
    <?php echo dcPage::jsLoad('js/jquery/jquery-ui.custom.js');?>
    <script type="text/javascript">
      var rslt_confirm_remove_songs_from_album = "<?php echo __('Are you sure you want to remove selected songs from album (%s)?');?>";
    </script>
    <script type="text/javascript" src="index.php?pf=rslt/js/admin.js"></script>
  </head>
  <body>
    <h2>
      <?php echo html::escapeHTML($core->blog->name); ?> &gt;
      <a href="<?php echo $p_url;?>#albums"><?php echo __('Albums');?></a> &gt; <?php echo $page_title;?>
    </h2>

    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <form action="<?php echo $p_url;?>" method="post" id="album-form">
      <p class="field">
	<label class="required title" for="album_title">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Title:');?>
	</label>
	<?php echo form::field('album_title', 100, 255, html::escapeHTML($album['title']), '');?>
      </p>
      <div class="lockable">
	<p class="field">
	  <label for="album_url">
	    <?php echo __('URL:');?>
	  </label>
	  <?php echo form::field('album_url', 100, 255, html::escapeHTML($album['url']), '');?>
	</p>
	<p class="form-note warn">
	  <?php echo __('Warning: If you set the URL manually, it may conflict with another album.');?>
	</p>
      </div>
      <p class="field">
	<label class="required" for="album_singer">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Singer:');?>
	</label>
	<?php echo form::field('album_singer', 100, 255, html::escapeHTML($album['singer']), '');?>
      </p>
      <p class="field">
	<label class="required" for="album_publication_date">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Publication date:');?>
	</label>
	<?php echo form::field('album_publication_date', 6, 4, html::escapeHTML($album['publication_date']), '');?>
      </p>
      <p>
	<?php echo form::hidden(array('p',''), 'rslt');?>
	<?php echo form::hidden(array('object',''), 'album');?>
	<?php echo form::hidden(array('action',''), $action);?>
	<?php echo $core->formNonce();?>
	<input type="submit" name="save_album" value="<?php echo __('Save'); ?>"/>
      </p>
    </form>

    <?php if (!empty($album['id'])):?>
    <h3><?php echo __('Tracklist');?></h3>
    <?php if (!empty($songs) && !$songs->isEmpty()):?>
    <form action="<?php echo $p_url;?>" method="post" id="songs-rank-form">
      <div class="songs">
	<ul>
	  <?php while ($songs->fetch()):?>
	  <li>
	    <input type="checkbox" name="songs[]" value="<?php echo (int) $songs->id;?>"/>
	    <input type="text" size="2" name="position[<?php echo $songs->id;?>]" value="<?php echo (int) $songs->rank;?>"/>
	    <?php echo $songs->title;?>&nbsp;-&nbsp;<?php echo $songs->singer;?>
	  </li>
	  <?php endwhile;?>
	</ul>
      </div>
      <div class="two-cols clearfix">
	<div class="col">
	  <p class="form-note hidden-if-no-js"><?php echo __('To rearrange songs order, move items by drag and drop, then click on “Save songs order” button.');?></p>
	  <p>
	    <span class="hidden-if-no-js">
	      <input type="submit" name="save_order" class="disabled" disabled="disabled" id="save-set-order" value="<?php echo __('Save songs order');?>"/>
	    </span> 
	    <?php echo form::hidden(array('p',''), 'rslt');?>
	    <?php echo form::hidden(array('object',''), 'album');?>
	    <?php echo form::hidden(array('album_id',''), $album['id']);?>
	    <?php echo $core->formNonce();?>
	  </p>
	</div>
	<div class="col">
	  <p class="checkboxes-helpers"></p>
	  <input type="submit" id="remove-songs" name="remove" class="delete" value="<?php echo __('Remove selected songs from album');?>"/>
	</div>
      </div>
    </form>    
    <?php else:?>
    <p><?php echo __('No song in that album');?></p>
    <?php endif;?>
    <?php endif;?>
  </body>
</html>


