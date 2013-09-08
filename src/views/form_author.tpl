<html>
  <head>
    <title><?php echo $page_title.' - '.__('Authors'); ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
  </head>
  <body>
    <h2>
      <?php echo html::escapeHTML($core->blog->name); ?> &gt;
      <a href="<?php echo $p_url;?>#authors"><?php echo __('Authors');?></a> &gt; <?php echo $page_title;?>
    </h2>

    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <form action="<?php echo $p_url;?>" method="post" id="type-form">
      <p class="field">
	<label class="required" for="type_label">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Firstname');?>
	</label>
	<?php echo form::field('author_firstname', 100, 255, html::escapeHTML($author['firstname']), '');?>
      </p>
      <p class="field">
	<label class="required" for="type_label">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Lastname');?>
	</label>
	<?php echo form::field('author_lastname', 100, 255, html::escapeHTML($author['lastname']), '');?>
      </p>
      <p>
	<?php echo form::hidden('p', 'rslt');?>
	<?php echo form::hidden('object', 'author');?>
	<?php echo form::hidden('action', $action);?>
	<?php echo $core->formNonce();?>
	<input type="submit" name="save_author" value="<?php echo __('Save'); ?>"/>
      </p>
    </form>
  </body>
</html>


