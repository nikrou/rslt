<html>
  <head>
    <title><?php echo $page_title.' - '.__('Persons'); ?></title>
  </head>
  <body>
    <h2>
      <?php echo html::escapeHTML($core->blog->name); ?> &gt;
      <a href="<?php echo $page_url;?>"><?php echo __('Persons');?></a> &gt; <?php echo $page_title;?>
    </h2>

    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <form action="<?php echo $page_url;?>" method="post" id="person-form">
      <p class="field">
	<label class="required title" for="person_title">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Name:');?>
	</label>
	<?php echo form::field('person_title', 100, 255, html::escapeHTML($person['title']), '');?>
      </p>
      <p class="field">
	<label class="required title" for="person_first_name">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Firstname:');?>
	</label>
	<?php echo form::field('person_first_name', 100, 255, html::escapeHTML($person['first_name']), '');?>
      </p>
      <p class="field">
	<label class="required title" for="person_last_name">
	  <abbr title="<?php echo __('Required field');?>">*</abbr>
	  <?php echo __('Lastname:');?>
	</label>
	<?php echo form::field('person_last_name', 100, 255, html::escapeHTML($person['last_name']), '');?>
      </p>
      <div class="lockable">
	<p class="field">
	  <label for="person_url">
	    <?php echo __('URL:');?>
	  </label>
	  <?php echo form::field('person_url', 100, 255, html::escapeHTML($person['url']), '');?>
	</p>
	<p class="form-note warn">
	  <?php echo __('Warning: If you set the URL manually, it may conflict with another person.');?>
	</p>
      </div>
      <p>
	<?php echo form::hidden(array('p',''), 'rslt');?>
	<?php if (!empty($person['id'])) { echo form::hidden('id', $person['id']);}?>
	<?php echo form::hidden(array('object',''), 'person');?>
	<?php echo form::hidden(array('action',''), $action);?>
	<?php echo $core->formNonce();?>
	<input type="submit" name="save_person" value="<?php echo __('Save'); ?>"/>
      </p>
    </form>
  </body>
</html>
