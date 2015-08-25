<?php
/**
 * Zood Framework
 */
require_once ('Smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = ZOODPP_APP.'/views';
$smarty->compile_dir = ZOODPP_ROOT.'/smarty/templates_c';
$smarty->cache_dir = ZOODPP_ROOT.'/smarty/cache';
$smarty->config_dir = ZOODPP_ROOT.'/smarty/configs';

?>