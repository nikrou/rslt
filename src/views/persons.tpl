<html>
  <head>
    <title><?php echo __('RSLT');?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
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
    <?php echo dcPage::breadcrumb(array(html::escapeHTML($core->blog->name) => '',  __('Persons') => ''));?>

    <?php if ($persons_counter==0):?>
    <p><strong><?php echo __('No person');?></strong></p>
    <?php else:?>
    <form action="<?php echo $page_url;?>" method="get" id="filters-form">
      <h3 class="out-of-screen-if-js"><?php echo $form_filter_title;?></h3>

      <div class="table">
	<div class="cell">
	  <h4><?php echo __('Search');?></h4>
	  <p>
	    <label for="aq" class="ib"><?php echo __('Title:');?></label>
	    <?php echo form::field('q',20,255,html::escapeHTML($q));?><br/>
	    <span class="form-note"><?php echo __('You can use wildcards (? or *) for search.');?></span>
	  </p>
	</div>

	<div class="cell">
	  <h4><?php echo __('Display options');?></h4>
	  <p><label for="sortby" class="ib"><?php echo __('Order by:');?></label>
	    <?php echo form::combo('sortby', $sortby_combo, $sortby);?>
	  </p>
	  <p><label for="order" class="ib"><?php echo __('Sort:');?></label>
	    <?php echo form::combo('order',$order_combo, $order);?>
	  </p>
	  <p>
	    <span class="label ib"><?php echo __('Show');?></span>
	    <label for="nbs" class="classic">
	      <?php echo form::field('nb',3,3,$nb_per_page), __('persons per page');?>
	    </label>
	  </p>
	</div>
      </div>
      <p>
	<input class="clearfix" type="submit" value="<?php echo __('Apply filters and display options');?>"/>
	<input type="hidden" name="p" value="rslt"/>
	<input type="hidden" name="object" value="person"/>
      </p>
    </form>

    <p class="infos"><?php printf(__('%d persons in database'), $persons_counter);?></p>
    <?php
       $persons_list->display($page, $nb_per_page,
    '<form action="'.$page_url.'" method="post" id="form-persons">'.'%s'.
      '<div class="two-cols clearfix">'.
	'<p class="col checkboxes-helpers"></p>'.
	'<p class="col right">'.
	  '<label for="persons_action" class="classic">'. __('Selected persons action:').'</label>'.
	  form::combo(array('action','persons_action'), $persons_action_combo, '', '').
	  '<input type="submit" name="do_action" value="'.__('ok').'"/>'.
	  '<input type="hidden" name="object" value="person"/>'.
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
