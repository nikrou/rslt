<html>
  <head>
    <title><?php echo __('RSLT');?></title>
    <?php echo dcPage::jsPageTabs($default_tab);?>
  </head>
  <body>
    <h2><?php echo html::escapeHTML($core->blog->name);?> &gt; RSLT</h2>
    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <?php if ($is_super_admin):?>
    <div class="multi-part" id="settings" title="<?php echo __('Settings');?>">
      <h3 class="hidden-if-js"><?php echo __('Settings');?></h3>
      <form action="<?php echo $page_url;?>" method="post" enctype="multipart/form-data">
	<div class="fieldset">
	  <h3><?php echo __('Plugin activation');?></h3>
	  <p>
	    <label class="classic" for="rslt_active">
	      <?php echo form::checkbox('rslt_active', 1, $rslt_active);?>
	      <?php echo __('Enable RSLT plugin');?>
	    </label>
	  </p>
	</div>
	<?php if ($rslt_active):?>
	<div class="fieldset">
	  <h4><?php echo __('URL');?></h4>
	  <p class="field">
	    <label class="classic" for="rslt_prefix_albums"><?php echo __('Albums page prefix:');?></label>
	    <?php echo form::field('rslt_prefix_albums', 60, 255, $rslt_prefix['albums']);?>
	  </p>
	  <p class="field">
	    <label class="classic" for="rslt_album_prefix"><?php echo __('Album page prefix:');?></label>
	    <?php echo form::field('rslt_prefix_album', 60, 255, $rslt_prefix['album']);?>
	  </p>
	  <p class="field">
	    <label class="classic" for="rslt_song_prefix"><?php echo __('Song page prefix:');?></label>
	    <?php echo form::field('rslt_prefix_song', 60, 255, $rslt_prefix['song']);?>
	  </p>
	</div>

	<div class="fieldset">
	  <h4><?php echo __('Directories for media');?></h4>
	  <p class="field">
	    <label class="classic" for="rslt_directory_albums"><?php echo __('Albums directory:');?></label>
	    <?php echo form::field('rslt_directory_albums', 60, 255, $rslt_directory['albums']);?>
	  </p>
	  <p class="field">
	    <label class="classic" for="rslt_directory_bios"><?php echo __('Bios directory:');?></label>
	    <?php echo form::field('rslt_directory_bios', 60, 255, $rslt_directory['bios']);?>
	  </p>
	  <p class="field">
	    <label class="classic" for="rslt_directory_supports"><?php echo __('Supports directory:');?></label>
	    <?php echo form::field('rslt_directory_support', 60, 255, $rslt_directory['supports']);?>
	  </p>
	</div>

	<?php endif;?>
	<p>
	  <input type="hidden" name="p" value="rslt"/>
	  <?php echo $core->formNonce();?>
	  <input type="submit" name="saveconfig" value="<?php echo __('Save configuration');?>" />
	</p>
      </form>
    </div>
    <?php endif;?>
    <?php dcPage::helpBlock('rslt');?>
  </body>
</html>
