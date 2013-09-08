<html>
  <head>
    <title>Restons sur leurs traces</title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
    <script type="text/javascript" src="index.php?pf=rslt/js/jquery.tabs.js"></script>
    <script type="text/javascript" src="index.php?pf=rslt/js/admin.js"></script>
  </head>
  <body>
    <h2><?php echo html::escapeHTML($core->blog->name); ?> &gt; RSLT</h2>
    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <div class="part-tabs">
      <ul>
	<?php foreach ($Tabs as $tab_id => $tab):?>
	<li<?php if (!empty($tab['class'])):echo ' class="', $tab['class'],'"';endif;?>>
	  <a href="#<?php echo $tab_id;?>"><?php echo $tab['label'];?></a>
	</li>
	<?php endforeach;?>
      </ul>
    </div>

    <?php if ($is_super_admin):?>
    <div class="multi-part" id="rslt_settings">
      <h3 class="hidden-if-js"><?php echo __('Settings'); ?></h3>
      <form action="<?php echo $p_url;?>" method="post" enctype="multipart/form-data">
	<div class="fieldset">
	  <h4><?php echo __('Plugin activation'); ?></h4>
	  <p>
	    <label class="classic" for="rslt_active">
	      <?php echo form::checkbox('rslt_active', 1, $rslt_active);?>
	      <?php echo __('Enable RSLT plugin');?>
	    </label>
	  </p>
	  <?php echo form::hidden('p','rslt');?>
	  <?php echo $core->formNonce();?>
	  <input type="submit" name="saveconfig" value="<?php echo __('Save configuration'); ?>" />
	</div>
	<?php if ($rslt_active):?>
	<div class="fieldset">
	  <h4><?php echo __('Advanced options'); ?></h4>
	  <p>TODO</p>
	</div>
	<?php endif;?>
      </form>
    </div>
    <?php endif;?>
    <?php if ($rslt_active):?>
    <div class="multi-part" id="rslt_authors">
      <h3 class="hidden-if-js"><?php echo __('Authors'); ?></h3>
      <p class="top-add">
	<a class="button add" href="<?php echo $p_url;?>&amp;object=author&amp;action=add"><?php echo __('New author');?></a>
      </p>
      <?php if ($authors->isEmpty()):?>
      <p><strong><?php echo __('No author');?></strong></p>
      <?php else:?>
      <form action="<?php echo $p_url;?>" method="post">
	<table class="clear authors" id="authors-list">
	  <thead>
	    <tr>
	      <th>&nbsp;</th>
	      <th><?php echo __('Firstname'), ' - ', __('Lastname');?></th>
	    </tr>
	  </thead>
	  <tbody>
	    <?php while ($authors->fetch()):?>
	    <tr>
	      <td>
		<?php echo form::checkbox(array('authors[]'), $authors->id, '', '', '');?>
	      </td>
	      <td class="maximal">
		<a href="<?php echo $p_url.'&amp;object=author&amp;action=edit&amp;id=',$authors->id;?>">
		<?php echo html::escapeHTML(text::cutString($authors->firstname, 50));?>&nbsp;-&nbsp;
		<?php echo html::escapeHTML(text::cutString($authors->lastname, 50));?>
	      </td>
	    </tr>
	    <?php endwhile;?>
	  </tbody>
	</table>
	<p class="col checkboxes-helpers"></p>
	<p>
	  <?php echo form::hidden('p', 'rslt');?>
	  <?php echo form::hidden('object', 'author');?>
	  <input type="submit" name="do_remove" class="delete" value="<?php echo __('Remove selected');?>"/>
	  <?php echo $core->formNonce();?>
	</p>
      </form>
      <?php endif;?>
    </div>

    <div class="multi-part" id="rslt_albums">
      <p class="top-add">
	<a class="button add" href="<?php echo $p_url;?>&amp;object=album&amp;action=add"><?php echo __('New album');?></a>
      </p>
      <h3 class="hidden-if-js"><?php echo __('Albums'); ?></h3>

      <?php if ($albums->isEmpty()):?>
      <p><strong><?php echo __('No album');?></strong></p>
      <?php else:?>
      <form action="<?php echo $p_url;?>" method="post">
	<table class="albums clear" id="albums-list">
	  <thead>
	    <tr>
	      <th>&nbsp;</th>
	      <th><?php echo __('Title');?></th>
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

    <div class="multi-part" id="rslt_songs">
      <h3 class="hidden-if-js"><?php echo __('Songs'); ?></h3>
      <p class="top-add">
	<a class="button add" href="<?php echo $p_url;?>&amp;object=song&amp;action=add"><?php echo __('New song');?></a>
      </p>

      <?php if ($songs->isEmpty()):?>
      <p><strong><?php echo __('No song');?></strong></p>
      <?php else:?>
      <form action="<?php echo $p_url;?>" method="post">
	<table class="songs clear" id="songs-list">
	  <thead>
	    <tr>
	      <th>&nbsp;</th>
	      <th><?php echo __('Title');?></th>
	    </tr>
	  </thead>
	  <tbody>
	    <?php while ($songs->fetch()):?>
	    <tr>
	      <td>
		<?php echo form::checkbox(array('songs[]'), $songs->id, '', '', '');?>
	      </td>
	      <td class="maximal">
		<a href="<?php echo $p_url.'&amp;object=song&amp;action=edit&amp;id=',$songs->id;?>">
		  <?php echo html::escapeHTML(text::cutString($songs->title, 50));?>
		</a>
	      </td>
	    </tr>
	    <?php endwhile;?>
	  </tbody>
	</table>
	<p class="col checkboxes-helpers"></p>
	<p>
	  <?php echo form::hidden('p', 'rslt');?>
	  <?php echo form::hidden('object', 'song');?>
	  <input type="submit" name="do_remove" class="delete" value="<?php echo __('Remove selected');?>"/>
	  <?php echo $core->formNonce();?>
	</p>
      </form>
      <?php endif;?>
    </div>
    <?php endif;?>
    <div class="multi-part" id="rslt_about">
      <h3 class="hidden-if-js"><?php echo __('About'); ?></h3>
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
