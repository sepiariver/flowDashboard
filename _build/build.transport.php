<?php
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define package */
define('PKG_NAME','flowDashboard');
define('PKG_NAME_LOWER',strtolower(PKG_NAME));
define('PKG_VERSION','0.0.1');
define('PKG_RELEASE','beta');

$root = dirname(dirname(__FILE__)).'/';
$sources= array (
    'root' => $root,
    'build' => $root .'_build/',
    'source_core' => $root.'core/components/'.PKG_NAME_LOWER,
    'lexicon' => $root . 'core/components/'.PKG_NAME_LOWER.'/lexicon/',
    'docs' => $root.'core/components/'.PKG_NAME_LOWER.'/docs/',
    'model' => $root.'core/components/'.PKG_NAME_LOWER.'/model/',
);
unset($root);

require_once $sources['root'] . 'config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx= new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO'); echo 'Packing '.PKG_NAME_LOWER.'-'.PKG_VERSION.'-'.PKG_RELEASE.'<pre>'; flush();

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->directory = dirname(dirname(__FILE__)).'/_packages/';
$builder->createPackage(PKG_NAME_LOWER,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER,false,true,'{core_path}components/'.PKG_NAME_LOWER.'/');
$modx->getService('lexicon','modLexicon');

/* modDashboardWidget */
$widgets = include 'widget.data.php';
if (is_array($widgets)) {
    $attributes = array (
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => array ('name'),
    );
    $ct = count($widgets);
    $idx = 0;
    foreach ($widgets as $widget) {
        $idx++;
        $vehicle = $builder->createVehicle($widget,$attributes);
        if ($ct == $idx) {
            $vehicle->resolve('file',array(
                'source' => $sources['source_core'],
                'target' => "return MODX_CORE_PATH . 'components/';",
            ));
        }
        $builder->putVehicle($vehicle);
    }
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($widgets).' default dashboard widgets.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not load dashboard widgets!'); flush();
}
unset ($widgets,$widget,$attributes,$ct,$idx);

/* now pack in the license file, readme and setup options */
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt'),
    /*'setup-options' => array(
        'source' => $sources['build'].'setup.options.php',
    ),*/
));
$modx->log(modX::LOG_LEVEL_INFO,'Packaged in package attributes.'); flush();

$modx->log(modX::LOG_LEVEL_INFO,'Packing...'); flush();
$builder->pack();

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

?>
