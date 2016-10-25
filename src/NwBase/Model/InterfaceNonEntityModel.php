<?php
/**
 * BeC Inteligência. (http://www.becinteligencia.com.br)
 *
 * @copyright 2013 - Copyright (c) BeC Inteligência (http://www.becinteligencia.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Model;

/**
 * Interface para tratamento do database para uma tabela do banco de dados
 *
 * @category NwBase
 * @package  NwBase\Model
 * @author   Edson Horácio Junior <edson.junior@becinteligencia.com.br>
 */
interface InterfaceNonEntityModel
{
    /**
     * Retorna nome do Schema do database
     *
     * @return string
     */
    public function getSchemaName();
}
