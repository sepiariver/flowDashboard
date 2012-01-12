<?php
$widgets = array();
$widgets[1]= $modx->newObject('modDashboardWidget');
$widgets[1]->fromArray(array (
  'name' => 'flowdashboard.title',
  'description' => 'flowdashboard.description',
  'type' => 'html',
  'size' => 'double',
  'content' => '<!--Flow Dashboard Widget by Sepia River-->
<iframe src="http://link.getflow.com/SFM" title="Flow Task Management" style="width:100%;height:100%;min-height:600px;"></iframe>',
  'namespace' => 'flowdashboard',
  'lexicon' => 'flowdashboard:default',
), '', true, true);

return $widgets;
