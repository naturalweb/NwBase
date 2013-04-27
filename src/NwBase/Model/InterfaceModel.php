<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
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
     * Retorna nome do Tabela no database
     * 
     * @return string
     */
    public function getTableName();
    
    /**
     * Retorna nome do Schema do database
     *
     * @return string
     */
    public function getSchemaName();
    
    /**
     * Retorna o object TableIdentifier, identifica a tabela
     *
     * @return \Zend\Db\Sql\TableIdentifier
     */
    public function getTableIdentifier();
    
    /**
     * Objeto TableGateway
     *  
     * @return \Zend\Db\TableGateway\TableGateway
     */
    public function getTableGateway();
    
    /**
     * Cria o objeto Select baseado no argumentos
     * 
     * @param Where|\Closure|string|array $where Condição da Busca
     *
     * @return \Zend\Db\Sql\Select
     */
    public function getSelect($where = null, $order = null, $limit = null, $offset = null);
    
    /**
     * Retorna o resultado da busca no objeto ResultSet
     * 
     * @param Where|\Closure|string|array $where Condição da Busca
     * @param string|array                $order Ordenação
     * 
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll($where = null, $order = null);
    
    /**
     * Busca o primeiro registro da condição da busca
     * 
     * @param Where|\Closure|string|array $where Condição da Busca
     *
     * @return \NwBase\Entity\InterfaceEntity 
     */
    public function fetchRow($where);
    
    /**
     * Faz a busca pela coluna(s) de chave primary
     * 
     * @param int|array $id valor do ID
     *
     * @return \NwBase\Entity\InterfaceEntity
     */
    public function findById($id);
}
