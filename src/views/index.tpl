<html>
  <head>
    <title>Restons sur leurs traces</title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
    <?php echo dcPage::jsPageTabs($default_tab);?>
    <script type="text/javascript">
      //<![CDATA[
      var rslt_confirm_delete = [];
      rslt_confirm_delete['songs'] = "<?php echo __('Are you sure you want to delete selected songs (%s)?');?>";
      rslt_confirm_delete['song'] = "<?php echo __('Are you sure you want to delete selected song?');?>";
      rslt_confirm_delete['albums'] = "<?php echo __('Are you sure you want to delete selected albums (%s)?');?>";
      rslt_confirm_delete['album'] = "<?php echo __('Are you sure you want to delete selected album?');?>";
      var rslt_filters = {show:"<?php echo __('Show filters');?>",hide:"<?php echo __('Hide filters');?>"};
      var rslt_albums_service = "<?php echo $rslt_albums_service;?>";
      //]]>
    </script>
    <script type="text/javascript" src="index.php?pf=rslt/js/jquery.ui.autocomplete.js"></script>
    <script type="text/javascript" src="index.php?pf=rslt/js/admin.js"></script>
  </head>
  <body>
    <h2><?php echo html::escapeHTML($core->blog->name);?> &gt; RSLT</h2>
    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <?php if ($is_super_admin):?>
    <div class="multi-part" id="settings" title="<?php echo __('Settings');?>">
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
    <?php if ($rslt_active):?>
    <div class="multi-part" id="person" title="<?php echo __('Person');?>">
      <h3 class="hidden-if-js"><?php echo __('Person');?></h3>
      <?php if (count($person_list->rows())==0):?>
      <p><strong><?php echo __('No person');?></strong></p>
      <?php else:?>
      <?php foreach($person_list as $person):?>
      <ul>
	<li><?php //echo $person;?></li>
      </ul>
      <?php endforeach;?>
      <?php endif;?>
    </div>

    <div class="multi-part" id="albums" title="<?php echo __('Albums');?>">
      <p class="top-add">
	<a class="button add" href="<?php echo $p_url;?>&amp;object=album&amp;action=add"><?php echo __('New album');?></a>
      </p>
      <h3 class="hidden-if-js"><?php echo __('Albums');?></h3>

      <p>
	<span class="filters-form-control<?php if ($active_albums_filters):?> open<?php endif;?>">
	  <?php if ($active_albums_filters):?>
	  <?php echo __('Hide filters');?>
	  <?php else:?>
	  <?php echo __('Show filters');?>
	  <?php endif;?>
	</span>
      </p>
      <form action="<?php echo $p_url;?>#albums" method="get" id="filters-albums-form" class="filters-form<?php if (!$active_albums_filters):?> hide<?php endif;?>">
	<div class="table">
	  <div class="cell">
	    <h4><?php echo __('Search');?></h4>
	    <p>
	      <label for="aq" class="ib"><?php echo __('Title:');?></label>
	      <?php echo form::field('aq',20,255,html::escapeHTML($aq));?><br/>
	      <span class="form-note"><?php echo __('You can use wildcards (? or *) for search.');?></span>
	    </p>
	    <h4><?php echo __('Filters');?></h4>
	    <p>
	      <label for="publication_date_id" class="ib"><?php echo __('Publication date:');?></label>
	      <?php echo form::combo('publication_date_id', $publication_date_combo, $publication_date_id);?>
	    </p>
	  </div>

	  <div class="cell">
	    <h4><?php echo __('Display options');?></h4>
	    <p><label for="sortby_album" class="ib"><?php echo __('Sort by:');?></label>
	    <?php echo form::combo('sortby_album', $sortby_albums_combo, $sortby_album);?>
	    </p>
	    <p><label for="order_album" class="ib"><?php echo __('Order:');?></label>
	    <?php echo form::combo('order_album',$order_combo, $order_album);?>
	    </p>
	    <p>
	      <span class="label ib"><?php echo __('Show');?></span>
	      <label for="nbs" class="classic">
		<?php echo form::field('nba',3,3,$nb_per_page_albums), __('albums per page');?>
	      </label>
	    </p>
	  </div>
	</div>
	<p>
	  <input class="clearfix" type="submit" value="<?php echo __('Apply filters and display options');?>"/>
	  <input type="hidden" name="p" value="rslt"/>
	</p>
      </form>

      <?php if ($albums_counter==0):?>
      <p><strong><?php echo __('No album');?></strong></p>
      <?php else:?>
      <p class="infos"><?php printf(__('%d albums in database'), $albums_counter);?></p>
      <?php
	 $albums_list->display($page_albums, $nb_per_page_albums,
      '<form action="'.$p_url.'" method="post" id="form-albums">'.'%s'.
	'<div class="two-cols clearfix">'.
	  '<p class="col checkboxes-helpers"></p>'.
	  '<p class="col right">'.
	    '<label for="albums_action" class="classic">'. __('Selected albums action:').'</label>'.
	    form::combo(array('action','albums_action'), $albums_action_combo, '', '').
	    '<input type="submit" name="do_action" value="'.__('ok').'"/>'.
	    '<input type="hidden" name="object" value="album"/>'.
	    $core->formNonce().
	    '</p>'.
	'</div>'.
      '</form>');
      ?>
      <?php endif;?>
    </div>

    <div class="multi-part" id="songs" title="<?php echo __('Songs');?>">
      <h3 class="hidden-if-js"><?php echo __('Songs');?></h3>
      <p class="top-add">
	<a class="button add" href="<?php echo $p_url;?>&amp;object=song&amp;action=add"><?php echo __('New song');?></a>
      </p>

      <p>
	<span class="filters-form-control<?php if ($active_songs_filters):?> open<?php endif;?>">
	  <?php if ($active_songs_filters):?>
	  <?php echo __('Hide filters');?>
	  <?php else:?>
	  <?php echo __('Show filters');?>
	  <?php endif;?>
	</span>
      </p>
      <form action="<?php echo $p_url;?>#songs" method="get" id="filters-songs-form" class="filters-form<?php if (!$active_songs_filters):?> hide<?php endif;?>">
	<div class="table">
	  <div class="cell">
	    <h4><?php echo __('Search');?></h4>
	    <p>
	      <label for="sq" class="ib"><?php echo __('Title:');?></label>
	      <?php echo form::field('sq',20,255,html::escapeHTML($sq));?><br/>
	      <span class="form-note"><?php echo __('You can use wildcards (? or *) for search.');?></span>
	    </p>
	    <h4><?php echo __('Filters');?></h4>
	    <p>
	      <label for="editor_id" class="ib"><?php echo __('Editor:');?></label>
	      <?php echo form::combo('editor_id', $editors_combo, $editor_id);?>
	    </p>
	    <p>
	      <label for="singer_id" class="ib"><?php echo __('Singer:');?></label>
	      <?php echo form::combo('singer_id', $singers_combo, $singer_id);?>
	    </p>
	  </div>

	  <div class="cell">
	    <h4><?php echo __('Filters');?></h4>
	    <p>
	      <label for="author_id" class="ib"><?php echo __('Author:');?></label>
	      <?php echo form::combo('author_id', $authors_combo, $author_id);?>
	    </p>
	    <p>
	      <label for="compositor_id" class="ib"><?php echo __('Compositor:');?></label>
	      <?php echo form::combo('compositor_id', $compositors_combo, $compositor_id);?>
	    </p>
	    <p>
	      <label for="adaptator_id" class="ib"><?php echo __('Adaptator:');?></label>
	      <?php echo form::combo('adaptator_id', $adaptators_combo, $adaptator_id);?>
	    </p>
	  </div>

	  <div class="cell">
	    <h4><?php echo __('Display options');?></h4>
	    <p><label for="sortby_song" class="ib"><?php echo __('Sort by:');?></label>
	    <?php echo form::combo('sortby_song', $sortby_songs_combo, $sortby_song);?>
	    </p>
	    <p><label for="order_song" class="ib"><?php echo __('Order:');?></label>
	    <?php echo form::combo('order_song',$order_combo, $order_song);?>
	    </p>
	    <p>
	      <span class="label ib"><?php echo __('Show');?></span>
	      <label for="nbs" class="classic">
		<?php echo form::field('nbs',3,3,$nb_per_page_songs), __('songs per page');?>
	      </label>
	    </p>
	  </div>
	</div>
	<p>
	  <input class="clearfix" type="submit" value="<?php echo __('Apply filters and display options');?>"/>
	  <input type="hidden" name="p" value="rslt"/>
	</p>
      </form>

      <?php if ($songs_counter==0):?>
      <p><strong><?php echo __('No song');?></strong></p>
      <?php else:?>
      <p class="infos"><?php printf(__('%d songs in database'), $songs_counter);?></p>
      <?php
      $songs_list->display($page_songs, $nb_per_page_songs,
      '<form action="'.$p_url.'" method="post" id="form-songs">'.'%s'.
	'<div class="two-cols clearfix">'.
	  '<p class="col checkboxes-helpers"></p>'.
	  '<p class="col right">'.
	    '<label for="songs_action" class="classic">'. __('Selected songs action:').'</label>'.
	    form::combo(array('action','songs_action'), $songs_action_combo, '', '').
	    '<input type="text" name="album_input" id="album-input"/>'.
	    '<input type="hidden" name="album_id" id="album-id" value=""/>'.
	    '<input type="submit" name="do_action" value="'.__('ok').'"/>'.
	    '<input type="hidden" name="object" value="song"/>'.
	    $core->formNonce().
	    '</p>'.
	    '<div id="albums_selection"></div>'.
	'</div>'.
      '</form>');
      ?>
      <?php endif;?>
    </div>
    <?php endif;?>
    <?php dcPage::helpBlock('rslt');?>
  </body>
</html>
