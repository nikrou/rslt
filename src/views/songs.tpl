<html>
  <head>
    <title><?php echo __('RSLT');?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
    <?php echo dcPage::jsPageTabs($default_tab);?>
    <script type="text/javascript">
      //<![CDATA[
      <?php echo dcPage::jsVar('dotclear.msg.show_filters', $show_filters ? 'true':'false');?>
      <?php echo dcPage::jsVar('dotclear.msg.filter_posts_list',$form_filter_title);?>
      <?php echo dcPage::jsVar('dotclear.msg.cancel_the_filter',__('Cancel filters and display options'));?>
      //]]>
    </script>
    <?php echo dcPage::jsLoad('js/filter-controls.js');?>
    <script type="text/javascript" src="index.php?pf=rslt/js/admin.js"></script>
  </head>
  <body>
    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <?php if ($rslt_active):?>
    <?php echo dcPage::breadcrumb(array(html::escapeHTML($core->blog->name) => '',  __('Songs') => ''));?>
    <p class="top-add">
      <a class="button add" href="<?php echo $page_url;?>&amp;action=add"><?php echo __('New song');?></a>
    </p>
    <form action="<?php echo $page_url;?>" method="get" id="filters-form">
      <h3 class="out-of-screen-if-js"><?php echo $form_filter_title;?></h3>

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
	<input type="hidden" name="object" value="song"/>
      </p>
    </form>

    <?php if ($songs_counter==0):?>
    <p><strong><?php echo __('No song');?></strong></p>
    <?php else:?>
    <p class="infos"><?php printf(__('%d songs in database'), $songs_counter);?></p>
    <?php
       $songs_list->display($page_songs, $nb_per_page_songs,
    '<form action="'.$page_url.'" method="post" id="form-songs">'.'%s'.
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

    <?php endif;?>
    <?php dcPage::helpBlock('rslt');?>
  </body>
</html>
