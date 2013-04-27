<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Paginator\Adapter;

use NwBase\Paginator\Adapter\DbTableGateway;
use NwBase\Model\InterfaceModel;

/**
 * Adapter do Paginator para trabalhar com objeto InterfaceModel
 *
 * @category   NwBase
 * @package    NwBase\Paginator
 * @subpackage Adapter
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class DbModel extends DbTableGateway
{
    /**
     * Construtor
     * 
     * @param InterfaceModel $model Object Model
     */
    public function __construct(InterfaceModel $model, $where = null, $order = null)
    {
        $tableGateway = $model->getTableGateway();
        
        parent::__construct($tableGateway, $where, $order);
    }
}
