<?php

class IndexController extends Zood_Controller_Action
{
    public function indexAction()
    {
        require_once ZOODPP_APP . '/models/TestModel.php';
        
        $list = TestModel::getTests();
        $this->setData('list',$list);
        $this->addResult(self::RESULT_SUCCESS, 'php', 'test/index.php');
        return self::RESULT_SUCCESS;
    }
}
// End ^ LF ^ UTF-8
