<html>
  <head>
    <title>Restons sur leurs traces</title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
    <script type="text/javascript" src="index.php?pf=rslt/js/jquery.tabs.js"></script>
    <script type="text/javascript" src="index.php?pf=rslt/js/admin.js"></script>
    <script type="text/javascript">
      var default_tab = "<?php echo $default_tab;?>";
      var rslt_confirm_delete_songs = "<?php echo __('Are you sure you want to delete selected songs (%s)?');?>";
    </script>
  </head>
  <body>
    <h2><?php echo html::escapeHTML($core->blog->name);?> &gt; RSLT</h2>
    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <?php if ($is_super_admin):?>
    <div class="multi-part" id="rslt_settings" title="<?php echo __('Settings');?>">
      <h3 class="hidden-if-js"><?php echo __('Settings');?></h3>
      <form action="<?php echo $p_url;?>" method="post" enctype="multipart/form-data">
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
	<p>
	  <?php echo form::hidden('p','rslt');?>
	  <?php echo $core->formNonce();?>
	  <input type="submit" name="saveconfig" value="<?php echo __('Save configuration');?>" />
	</p>
      </form>
    </div>
    <?php endif;?>
    <?php if ($rslt_active):?>
    <div class="multi-part" id="rslt_authors" title="<?php echo __('Authors');?>">
      <h3 class="hidden-if-js"><?php echo __('Authors');?></h3>
      <p class="top-add">
	<a class="button add" href="<?php echo $p_url;?>&amp;object=author&amp;action=add"><?php echo __('New author');?></a>
      </p>
      <?php if (empty($Authors)):?>
      <p><strong><?php echo __('No author');?></strong></p>
      <?php else:?>
      <?php foreach ($Authors as $author):?>
      <ul>
	<li><?php echo $author;?></li>
      </ul>
      <?php endforeach;?>
      <?php endif;?>
    </div>

    <div class="multi-part" id="rslt_albums" title="<?php echo __('Albums');?>">
      <p class="top-add">
	<a class="button add" href="<?php echo $p_url;?>&amp;object=album&amp;action=add"><?php echo __('New album');?></a>
      </p>
      <h3 class="hidden-if-js"><?php echo __('Albums');?></h3>

      <?php if ($albums->isEmpty()):?>
      <p><strong><?php echo __('No album');?></strong></p>
      <?php else:?>
      <form action="<?php echo $p_url;?>" method="post">
	<table class="albums clear" id="albums-list">
	  <thead>
	    <tr>
	      <th>&nbsp;</th>
	      <th><?php echo __('Title');?></th>
	      <th><?php echo __('Singer');?></th>
	    </tr>
	  </thead>
	  <tbody>
	    <?php while ($albums->fetch()):?>
	    <tr>
	      <td>
		<?php echo form::checkbox(array('albums[]'), $albums->id, '', '', '');?>
	      </td>
	      <td class="maximal">
		<a href="<?php echo $p_url.'&amp;object=album&amp;action=edit&amp;id=',$albums->id;?>">
		  <?php echo html::escapeHTML(text::cutString($albums->title, 50));?>
		</a>
	      </td>
	      <td class="nowrap">
		<?php echo html::escapeHTML($albums->singer);?>		
	      </td>
	    </tr>
	    <?php endwhile;?>
	  </tbody>
	</table>
	<p class="col checkboxes-helpers"></p>
	<p>
	  <?php echo form::hidden('p', 'rslt');?>
	  <?php echo form::hidden('object', 'album');?>
	  <input type="submit" name="do_remove" class="delete" value="<?php echo __('Remove selected');?>"/>
	  <?php echo $core->formNonce();?>
	</p>
      </form>
      <?php endif;?>
    </div>

    <div class="multi-part" id="rslt_songs" title="<?php echo __('Songs');?>">
      <h3 class="hidden-if-js"><?php echo __('Songs');?></h3>
      <p class="top-add">
	<a class="button add" href="<?php echo $p_url;?>&amp;object=song&amp;action=add"><?php echo __('New song');?></a>
      </p>

      <?php if ($songs_counter==0):?>
      <p><strong><?php echo __('No song');?></strong></p>
      <?php else:?>
      <form action="<?php echo $p_url;?>" method="post" id="form-songs">
	<p class="infos"><?php printf(__('%d songs in database'), $songs_counter);?>
	<?php $songs_list->display($page, $nb_per_page);?>
	<div class="two-cols clearfix">
	  <p class="col checkboxes-helpers"></p>
	  <p class="col right">
	    <label for="songs_action" class="classic">
	      <?php echo __('Selected songs action:');?>
	    </label>
	    <?php echo form::combo('action', $songs_action_combo, '', '');?>
	    <input type="hidden" name="object" value="song"/>
	    <input type="submit" name="do_remove" value="<?php echo __('ok');?>"/>
	    <?php echo $core->formNonce();?>
	  </p>
	</div>
      </form>
      <?php endif;?>
    </div>

    <div class="multi-part" id="rslt_maintenance" title="<?php echo __('Maintenance');?>">
      <h3 class="hidden-if-js"><?php echo __('Maintenance');?></h3>

      <form action="<?php echo $p_url;?>" method="post" >
	<p>
	  <input type="submit" value="<?php echo __('Load songs csv file');?>"/>
	  <input type="hidden" name="action" value="load"/>
	  <input type="hidden" name="file" value="songs"/>
	  <?php echo $core->formNonce();?>
	</p>
      </form>
    </div>
    <?php endif;?>
    <div class="multi-part" id="rslt_about" title="<?php echo __('About');?>">
      <h3 class="hidden-if-js"><?php echo __('About');?></h3>
      <p>
	<?php echo __('If you want more informations on that plugin or have new ideas to develope it, or want to submit a bug or need help (to install or configure it) or for anything else ...');?></p>
      <p>
	<?php printf(__('Go to %sthe dedicated page%s in'),
	      '<a href="http://www.nikrou.net/pages/rslt">',
	      '</a>');?>
	<a href="http://www.nikrou.net/">Le journal de nikrou</a>
      </p>
      <p><?php echo __('Made by:');?>
	<a href="http://www.nikrou.net/contact">Nicolas</a> (nikrou)
      </p>
    </div>
    <?php dcPage::helpBlock('rslt');?>
  </body>
</html>
