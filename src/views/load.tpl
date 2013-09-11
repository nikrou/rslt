<html>
  <head>
    <title><?php echo $page_title.' - '.__('Songs'); ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
  </head>
  <body>
    <h2>
      <?php echo html::escapeHTML($core->blog->name); ?> &gt;
      <a href="<?php echo $p_url;?>#songs"><?php echo __('Songs');?></a> &gt; <?php echo $page_title;?>
    </h2>

    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>
      <?php if (!empty($songs)):?>
      <ul>
	<?php foreach ($songs as $song):?>
	<li><?php printf('%s by %s (%s)', $song[1], $song[2], $song[0]);?></li>
	<?php endforeach;?>
      </ul>
      <?php endif;?>      
  </body>
</html>
