<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Paginator\Adapter;

use Zend\Paginator\Adapter\AdapterInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\TableGateway\TableGateway;

/**
 * Adapter do Paginator para trabalhar com objeto TableGateway
 *
 * @category   NwBase
 * @package    NwBase\Paginator
 * @subpackage Adapter
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class DbTableGateway extends DbSelect
{
    /**
     * Construnct
     * 
     * @param TableGateway                $tableGateway Objeto TableGateway
     * @param Where|\Closure|string|array $where        Condição da busca
     * @param array|string                $order        Ordenação
     * 
     * @return void
     */
    public function __construct(TableGateway $tableGateway, $where = null, $order = null)
    {
        $select = $tableGateway->getSql()->select();
        if ($where) {
            $select->where($where);
        }
        
        if ($order) {
            $select->order($order);
        }
        
        $dbAdapter          = $tableGateway->getAdapter();
        $resultSetPrototype = $tableGateway->getResultSetPrototype();
        
        parent::__construct($select, $dbAdapter, $resultSetPrototype);
    }
}
