    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

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
      <h4><?php echo __('Advanced options');?></h4>
      <p class="field">
	<label class="classic" for="rslt_albums_prefix"><?php echo __('Albums page prefix:');?></label>
	<?php echo form::field('rslt_albums_prefix', 60, 255, $rslt_albums_prefix);?>
      </p>
      <p class="field">
	<label class="classic" for="rslt_album_prefix"><?php echo __('Album page prefix:');?></label>
	<?php echo form::field('rslt_album_prefix', 60, 255, $rslt_album_prefix);?>
      </p>
      <p class="field">
	<label class="classic" for="rslt_song_prefix"><?php echo __('Song page prefix:');?></label>
	<?php echo form::field('rslt_song_prefix', 60, 255, $rslt_song_prefix);?>
      </p>
    </div>
    <?php endif;?>
