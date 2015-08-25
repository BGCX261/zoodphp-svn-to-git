<?php
require_once dirname(__FILE__).'/../init.php';

$smarty->assign('list', $this->getData('list'));
$smarty->display('test/template/index.html');
?>