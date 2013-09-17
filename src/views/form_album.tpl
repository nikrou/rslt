<html>
  <head>
    <title><?php echo $page_title.' - '.__('Albums'); ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
  </head>
  <body>
    <h2>
      <?php echo html::escapeHTML($core->blog->name); ?> &gt;
      <a href="<?php echo $p_url;?>#albums"><?php echo __('Albums');?></a> &gt; <?php echo $page_title;?>
    </h2>

    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <form action="<?php echo $p_url;?>" method="post" id="type-form">
      <p class="field">
	<label class="required" for="album_title">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Title:');?>
	</label>
	<?php echo form::field('album_title', 100, 255, html::escapeHTML($album['title']), '');?>
      </p>
      <p class="field">
	<label class="required" for="album_url">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('URL:');?>
	</label>
	<?php echo form::field('album_url', 100, 255, html::escapeHTML($album['url']), '');?>
      </p>
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
	<?php echo form::field('album_publication_date', 100, 255, html::escapeHTML($album['publication_date']), '');?>
      </p>
      <p>
	<?php echo form::hidden('p', 'rslt');?>
	<?php echo form::hidden('object', 'album');?>
	<?php echo form::hidden('action', $action);?>
	<?php echo $core->formNonce();?>
	<input type="submit" name="save_album" value="<?php echo __('Save'); ?>"/>
      </p>
    </form>
  </body>
</html>


