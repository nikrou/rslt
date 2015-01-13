<html>
  <head>
    <title><?php echo __('RSLT');?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
    <script type="text/javascript">
      //<![CDATA[
      var rslt_confirm_delete = [];
      rslt_confirm_delete['albums'] = "<?php echo __('Are you sure you want to delete selected albums (%s)?');?>";
      rslt_confirm_delete['album'] = "<?php echo __('Are you sure you want to delete selected album?');?>";
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
    <?php echo dcPage::breadcrumb(array(html::escapeHTML($core->blog->name) => '',  __('Albums') => ''));?>
    <p class="top-add">
      <a class="button add" href="<?php echo $page_url;?>&amp;action=add"><?php echo __('New album');?></a>
    </p>

    <form action="<?php echo $page_url;?>" method="get" id="filters-form">
      <h3 class="out-of-screen-if-js"><?php echo $form_filter_title;?></h3>

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
	<input type="hidden" name="object" value="album"/>
      </p>
    </form>

    <?php if ($albums_counter==0):?>
    <p><strong><?php echo __('No album');?></strong></p>
    <?php else:?>
    <p class="infos"><?php printf(__('%d albums in database'), $albums_counter);?></p>
    <?php
       $albums_list->display($page_albums, $nb_per_page_albums,
    '<form action="'.$page_url.'" method="post" id="form-albums">'.'%s'.
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

    <?php endif;?>
    <?php dcPage::helpBlock('rslt');?>
  </body>
</html>
