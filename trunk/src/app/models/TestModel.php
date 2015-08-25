<?php

require_once dirname(__FILE__) .'/Table/TestTable.php';

class TestModel
{
    public static function getTests()
    {
        return TestTable::instance()->getTests();
    }
}