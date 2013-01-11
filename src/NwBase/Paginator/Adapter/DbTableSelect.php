<?php

namespace NwBase\Paginator\Adapter;

use Zend\Paginator\Adapter\AdapterInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Select;
use NwBase\Model\InterfaceModel;

class DbTableSelect implements AdapterInterface
{
    /**
     * 
     * @var DbSelect
     */
    private $_dbSelect;
    
    public function __construct(Select $select, InterfaceModel $model)
    {
        $tableGateway       = $model->getTableGateway();
        $dbAdapter          = $tableGateway->getAdapter();
        $resultSetPrototype = $tableGateway->getResultSetPrototype();
        
        $this->_dbSelect = new DbSelect($select, $dbAdapter, $resultSetPrototype);
    }
    
    /**
     * Returns an array of items for a page.
     *
     * @param  integer $offset           Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return Zend\Db\ResultSet\ResultSet
     */
    public function getItems($offset, $itemCountPerPage)
    {
        return $this->_dbSelect->getItems($offset, $itemCountPerPage);
    }
    
    /**
     * Returns the total number of rows in the result set.
     *
     * @return integer
     */
    public function count()
    {
        return $this->_dbSelect->count();
    }
}
