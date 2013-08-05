<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Db\Sql\Predicate;

use Zend\Db\Sql\Predicate;

/**
 * Predicate Like, modificado para o specification, para NOT  ILIKE 
 * Utilizado para banco de dados case sensitive, ex: postgresql
 *
 * @category NwBase
 * @package  NwBase\Db\Sql\Predicate
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class NotILike extends Predicate\Like
{
    /**
     * @var string
     */
    protected $specification = '%1$s NOT ILIKE %2$s';
}
