<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Ahdong ( GTalk: ahdong.com@gmail.com )
 * @since      Mar 24, 2009
 * @version    SVN: $$Id$$
 */

Zood_Loader::loadClass('Zood_Db_Table_Abstract');

class TestTable extends Zood_Db_Table_Abstract
{
    /** Table name */
    protected $_name    = 'test';
    
    protected $_primary = 'testid';
    
    
    public function getTests()
    {
        $select = $this->getAdapter()->select()->from('test','*');
        $rows = $select->query()->fetchAll();
        return $rows;
    }

    /**
     * Insert new row
     *
     * Ensure that a timestamp is set for the created field.
     * 
     * @param  array $data 
     * @return int
     */
    public function insert(array $data)
    {
        $data['ctime'] = date('Y-m-d H:i:s');
        return parent::insert($data);
    }

    /**
     * Override updating
     *
     * Do not allow updating of entries
     * 
     * @param  array $data 
     * @param  mixed $where 
     * @return void
     * @throws Exception
     */
    public function update(array $data, $where)
    {
        throw new Exception('Cannot update guestbook entries');
    }
    
    /**
     * Singleton
     * 
     * @return TestTable
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}


// End ^ LF ^ UTF-8
