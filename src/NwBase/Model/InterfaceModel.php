<?php

namespace NwBase\Model;

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
    public function fetchAll(array $where = null);
    
    /**
     * @param mixed $where
     *
     * @return 
     */
    public function fetchRow(array $where);
    
    /**
     * @param int $id
     *
     * @return 
     */
    public function findById($id);
}
