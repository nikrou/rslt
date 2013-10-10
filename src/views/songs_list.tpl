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

    <pre>
      <?php print_r($_POST);?>
    </pre>
  </body>
</html>
