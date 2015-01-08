<html>
  <head>
    <title><?php echo __('RSLT');?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.php?pf=rslt/css/admin.css"/>
    <?php echo dcPage::jsPageTabs($default_tab);?>
    <script type="text/javascript" src="index.php?pf=rslt/js/admin.js"></script>
  </head>
  <body>
    <?php if (!empty($message)):?>
    <p class="message"><?php echo $message;?></p>
    <?php endif;?>

    <?php if ($rslt_active):?>
    <?php echo dcPage::breadcrumb(array(html::escapeHTML($core->blog->name) => '',  __('People') => ''));?>

    <?php if ($person_list->isEmpty()):?>
    <p><strong><?php echo __('No person');?></strong></p>
    <?php else:?>
    <p class="infos"><?php printf(__('%d persons in database'), $person_list->count());?></p>
    <ul>
      <?php foreach ($person_list as $person):?>
      <li><?php echo $person->name;?></li>
      <?php endforeach;?>
    </ul>
    <?php endif;?>
    <?php endif;?>
    <?php dcPage::helpBlock('rslt');?>
  </body>
</html>
