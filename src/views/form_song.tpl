<html>
  <head>
    <title><?php echo $page_title.' - '.__('Songs'); ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
  </head>
  <body>
    <h2>
      <?php echo html::escapeHTML($core->blog->name); ?> &gt;
      <a href="<?php echo $p_url;?>&amp;action=index#songs"><?php echo __('Songs');?></a> &gt; <?php echo $page_title;?>
    </h2>

    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <form action="<?php echo $p_url;?>" method="post" id="form-song">
      <p class="field">
	<label class="required" for="song_title">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Title:');?>
	</label>
	<?php echo form::field('song_title', 100, 255, html::escapeHTML($song['title']), '');?>
      </p>
      <p class="field">
	<label class="required" for="song_author">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Author:');?>
	</label>
	<?php echo form::field('song_author', 100, 255, html::escapeHTML($song['author']), '');?>
      </p>
      <p class="field">
	<label class="required" for="song_singer">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Singer:');?>
	</label>
	<?php echo form::field('song_singer', 100, 255, html::escapeHTML($song['singer']), '');?>
      </p>
      <p class="field">
	<label class="required" for="song_publication_date">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Publication date:');?>
	</label>
	<?php echo form::field('song_publication_date', 100, 255, html::escapeHTML($song['publication_date']), '');?>
      </p>
      <p>
	<?php echo form::hidden('p', 'rslt');?>
	<?php echo form::hidden('object', 'song');?>
	<?php echo form::hidden('action', $action);?>
	<?php echo $core->formNonce();?>
	<input type="submit" name="save_song" value="<?php echo __('Save'); ?>"/>
      </p>
    </form>
  </body>
</html>


