<?php
require_once dirname(__FILE__).'/../init.php';

$smarty->assign($this->getData());

$smarty->display('login/template/index.html');
?>