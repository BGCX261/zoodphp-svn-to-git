<?php

Zood_Loader::loadClass('Zood_Controller_Action');

class TestController extends Zood_Controller_Action
{
	public function indexAction()
	{
        require_once ZOODPP_APP . '/models/TestModel.php';
        
        $list = TestModel::getTests();
        $this->setData('list',$list);
        $this->addResult(self::RESULT_SUCCESS, 'php', 'test/test.php');
        return self::RESULT_SUCCESS;
	}
	
	public function testAction()
	{
		Zood_Util::print_r('another action:testAction','testAction');
	}
}
// End ^ LF ^ UTF-8
