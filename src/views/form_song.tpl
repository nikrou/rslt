<html>
  <head>
    <title><?php echo $page_title.' - '.__('Songs'); ?></title>
    <script type="text/javascript" src="index.php?pf=rslt/js/admin.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/select2.css"/>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
    <script type="text/javascript">
      var rslt_person_service = "<?php echo $rslt_person_service;?>";
    </script>
    <script type="text/javascript" src="index.php?pf=rslt/js/select2.js"></script>
    <script type="text/javascript" src="index.php?pf=rslt/js/select2_locale_fr.js"></script>
    <script type="text/javascript" src="index.php?pf=rslt/js/person.js"></script>
    <script type="text/javascript">
      var all_elements = [];
      <?php foreach ($json as $field => $ids):?>
      all_elements['<?php echo $field;?>'] = <?php echo $ids;?>;
      <?php endforeach;?>
    </script>
  </head>
  <body>
    <h2>
      <?php echo html::escapeHTML($core->blog->name); ?> &gt;
      <a href="<?php echo $page_url;?>&amp;action=index#songs"><?php echo __('Songs');?></a> &gt; <?php echo $page_title;?>
    </h2>

    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <form action="<?php echo $page_url;?>" method="post" id="song-form">
      <p class="field">
	<label class="required title" for="song_title">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Title:');?>
	</label>
	<?php echo form::field('song_title', 100, 255, html::escapeHTML($song['title']), '');?>
      </p>
      <div class="lockable">
	<p class="field">
	  <label for="song_url"><?php echo __('URL:');?></label>
	  <?php echo form::field('song_url', 100, 255, html::escapeHTML($song['url']), '');?>
	</p>
	<p class="form-note warn">
	  <?php echo __('Warning: If you set the URL manually, it may conflict with another song.');?>
	</p>
      </div>
      <p class="field">
	<label class="required" for="song_author">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Author:');?>
	</label>
	<input type="hidden" data-elements="author" data-placeholder="<?php echo __('Author');?>" class="select2" name="song_author" value="<?php echo html::escapeHTML($song['author']);?>">
      </p>
      <p class="field">
	<label class="required" for="song_compositor">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Compositor:');?>
	</label>
	<input type="hidden" data-elements="compositor" data-placeholder="<?php echo __('Compositor');?>" class="select2" name="song_compositor" value="<?php echo html::escapeHTML($song['compositor']);?>">
      </p>
      <p class="field">
	<label class="required" for="song_adaptator">
	  <?php echo __('Adaptator:');?>
	</label>
	<input type="hidden" data-elements="adaptator" data-placeholder="<?php echo __('Adaptator');?>" class="select2" name="song_adaptator" value="<?php echo html::escapeHTML($song['adaptator']);?>">
      </p>
      <p class="field">
	<label class="required" for="song_singer">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Singer:');?>
	</label>
	<input type="hidden" data-elements="singer" data-placeholder="<?php echo __('Singer');?>" class="select2" name="song_singer" value="<?php echo html::escapeHTML($song['singer']);?>">
      </p>
      <p class="field">
	<label class="required" for="song_editor">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Editor:');?>
	</label>
	<input type="hidden" data-elements="editor" data-placeholder="<?php echo __('Editor');?>" class="select2" name="song_editor" value="<?php echo html::escapeHTML($song['editor']);?>">
      </p>
      <p class="field">
	<label class="required" for="song_publication_date">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Publication date:');?>
	</label>
	<?php echo form::field('song_publication_date', 6, 4, html::escapeHTML($song['publication_date']), '');?>
      </p>
      <p>
	<?php echo form::hidden('p', 'rslt');?>
	<?php if (!empty($song['id'])) { echo form::hidden('id', $song['id']);}?>
	<?php echo form::hidden('object', 'song');?>
	<?php echo form::hidden('action', $action);?>
	<?php echo $core->formNonce();?>
	<input type="submit" name="save_song" value="<?php echo __('Save'); ?>"/>
      </p>
    </form>
  </body>
</html>
