<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright  Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license    BSD-3-Clause
 * @package    NwBase\Paginator
 * @subpackage Adapter
 */
namespace NwBase\Paginator\Adapter;

use Zend\Paginator\Adapter\AdapterInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Select;
use NwBase\Model\InterfaceModel;

/**
 * Adapter do Paginator para trabalhar com objeto InterfaceModel
 *
 * @category   NwBase
 * @package    NwBase\Paginator
 * @subpackage Adapter
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class DbTableSelect implements AdapterInterface
{
    /** 
     * @var DbSelect
     */
    private $_dbSelect;
    
    /**
     * Construtor
     * 
     * @param Select         $select Object Select
     * @param InterfaceModel $model  Object Model
     */
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
     * @param int $offset           Page offset
     * @param int $itemCountPerPage Number of items per page
     * 
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
