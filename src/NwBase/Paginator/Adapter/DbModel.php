<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Paginator\Adapter;

use Zend\Paginator\Adapter\DbSelect;
use NwBase\Model\InterfaceModel;

/**
 * Adapter do Paginator para trabalhar com objeto InterfaceModel
 *
 * @category   NwBase
 * @package    NwBase\Paginator
 * @subpackage Adapter
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class DbModel extends DbSelect
{
    /**
     * Construtor
     * 
     * @param InterfaceModel $model Object Model
     */
    public function __construct(InterfaceModel $model, $where = null, $order = null)
    {
        $select = $model->getSelect($where, $order);
        
        $tableGateway       = $model->getTableGateway();
        $dbAdapter          = $tableGateway->getAdapter();
        $resultSetPrototype = $tableGateway->getResultSetPrototype();
        
        parent::__construct($select, $dbAdapter, $resultSetPrototype);
    }
}
