<?php
$widgets = array();
$widgets[1]= $modx->newObject('modDashboardWidget');
$widgets[1]->fromArray(array (
  'name' => 'flowdashboard.title',
  'description' => 'flowdashboard.description',
  'type' => 'html',
  'size' => 'double',
  'content' => '[[++core_path]]components/flowdashboard/widget.php',
  'namespace' => 'flowdashboard',
  'lexicon' => 'flowdashboard:default',
), '', true, true);

return $widgets;
