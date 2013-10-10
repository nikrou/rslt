<html>
  <head>
    <title>Restons sur leurs traces</title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
    <?php echo dcPage::jsPageTabs($default_tab);?>
    <script type="text/javascript">
      var rslt_confirm_delete_songs = "<?php echo __('Are you sure you want to delete selected songs (%s)?');?>";
      var rslt_confirm_delete_albums = "<?php echo __('Are you sure you want to delete selected albums (%s)?');?>";
      var rslt_filters = {show:"<?php echo __('Show filters');?>",hide:"<?php echo __('Hide filters');?>"};
    </script>
    <script type="text/javascript" src="index.php?pf=rslt/js/admin.js"></script>
  </head>
  <body>
    <h2><?php echo html::escapeHTML($core->blog->name);?> &gt; RSLT</h2>
    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <?php if ($rslt_active):?>
    <div class="multi-part" id="authors" title="<?php echo __('Authors');?>">
      <h3 class="hidden-if-js"><?php echo __('Authors');?></h3>
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

    <div class="multi-part" id="albums" title="<?php echo __('Albums');?>">
      <p class="top-add">
	<a class="button add" href="<?php echo $p_url;?>&amp;object=album&amp;action=add"><?php echo __('New album');?></a>
      </p>
      <h3 class="hidden-if-js"><?php echo __('Albums');?></h3>

      <?php if ($albums_counter==0):?>
      <p><strong><?php echo __('No album');?></strong></p>
      <?php else:?>
      <p class="infos"><?php printf(__('%d albums in database'), $albums_counter);?></p>
      <?php $albums_list->display($page_albums, $nb_per_page_albums);?>

      <form action="<?php echo $p_url;?>" method="post" id="form-albums">
	<div class="two-cols clearfix">
	  <p class="col checkboxes-helpers"></p>
	  <p class="col right">
	    <label for="albums_action" class="classic">
	      <?php echo __('Selected albums action:');?>
	    </label>
	    <?php echo form::combo(array('action','albums_action'), $albums_action_combo, '', '');?>
	    <input type="hidden" name="object" value="album"/>
	    <input type="submit" name="do_action" value="<?php echo __('ok');?>"/>
	    <?php echo $core->formNonce();?>
	  </p>
	</div>
      </form>
      <?php endif;?>
    </div>

    <div class="multi-part" id="songs" title="<?php echo __('Songs');?>">
      <h3 class="hidden-if-js"><?php echo __('Songs');?></h3>
      <p class="top-add">
	<a class="button add" href="<?php echo $p_url;?>&amp;object=song&amp;action=add"><?php echo __('New song');?></a>
      </p>

      <p>
	<a id="filters-songs" class="form-control<?php if ($active_filters):?> open<?php endif;?>" href="#">
	  <?php if ($active_filters):?>
	  <?php echo __('Hide filters');?>
	  <?php else:?>
	  <?php echo __('Show filters');?>
	  <?php endif;?>
	</a>
      </p>
      <form action="<?php echo $p_url;?>#songs" method="get" id="filters-songs-form" class="filters-form<?php if (!$active_filters):?> hide<?php endif;?>">
	<div class="table">
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
	
	  <div class="cell filters-sibling-cell">
	    <p>
	      <label for="editor_id" class="ib"><?php echo __('Editor:');?></label>
	      <?php echo form::combo('editor_id', $editors_combo, $editor_id);?>
	    </p>
	    <p>
	      <label for="singer_id" class="ib"><?php echo __('Singer:');?></label>
	      <?php echo form::combo('singer_id', $singers_combo, $singer_id);?>
	    </p>
	  </div>
	
	  <div class="cell filters-options">
	    <h4><?php echo __('Display options');?></h4>
	    <p><label for="sortby" class="ib"><?php echo __('Order by:');?></label>
	    <?php echo form::combo('sortby', $sortby_combo, $sortby);?>
	    </p>
	    <p><label for="order" class="ib"><?php echo __('Sort:');?></label>
	    <?php echo form::combo('order',$order_combo,$order);?>
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
	  <?php echo form::hidden('p','rslt');?>
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
	    '<input type="hidden" name="object" value="song"/>'.
	    '<input type="submit" name="do_action" value="'.__('ok').'"/>'.
	    $core->formNonce().
	  '</p>'.
	'</div>'.
      '</form>');
      ?>
      <?php endif;?>
    </div>

    <div class="multi-part" id="maintenance" title="<?php echo __('Maintenance');?>">
      <h3 class="hidden-if-js"><?php echo __('Maintenance');?></h3>

      <form action="<?php echo $p_url;?>" method="post" >
	<p>
	  <input type="submit" value="<?php echo __('Load songs csv file');?>"/>
	  <input type="hidden" name="action" value="load"/>
	  <input type="hidden" name="file" value="songs"/>
	  <input type="hidden" name="object" value="song"/>	  
	  <?php echo $core->formNonce();?>
	</p>
      </form>

      <form action="<?php echo $p_url;?>" method="post" >
	<p>
	  <input type="submit" value="<?php echo __('Load albums csv file');?>"/>
	  <input type="hidden" name="action" value="load"/>
	  <input type="hidden" name="file" value="albums"/>
	  <input type="hidden" name="object" value="album"/>	  
	  <?php echo $core->formNonce();?>
	</p>
      </form>

      <form action="<?php echo $p_url;?>" method="post" >
	<p>
	  <input type="submit" value="<?php echo __('Load songs in albums csv file');?>"/>
	  <input type="hidden" name="action" value="load"/>
	  <input type="hidden" name="file" value="albums_songs"/>
	  <input type="hidden" name="object" value="album_song"/>	  
	  <?php echo $core->formNonce();?>
	</p>
      </form>
    </div>
    <?php endif;?>
    <div class="multi-part" id="about" title="<?php echo __('About');?>">
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
