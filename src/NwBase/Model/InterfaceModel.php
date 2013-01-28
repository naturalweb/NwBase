<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright  Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license    BSD-3-Clause
 * @package    NwBase\Model
 */
namespace NwBase\Model;

/**
 * Interface para tratamento do database para uma tabela do banco de dados
 *
 * @category NwBase
 * @package  NwBase\Model
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
interface InterfaceModel
{
    /**
     * @return string
     */
    public function getTableName();
    
    /**
     * @return \Zend\Db\TableGateway\TableGateway
     */
    public function getTableGateway();
    
    /**
     * @param undined $where
     *
     * @return \Zend\Db\Sql\Select
     */
    public function getSelect($where = null, $order = null, $limit = null, $offset = null);
    
    /**
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($where = null);
    
    /**
     * @param mixed $where
     *
     * @return 
     */
    public function fetchRow($where);
    
    /**
     * @param int $id
     *
     * @return 
     */
    public function findById($id);
}
