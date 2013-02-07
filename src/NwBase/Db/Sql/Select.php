<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause
 * @package   NwBase\Db\Sql
 */
namespace NwBase\Db\Sql;

use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select as Zend_Select;
use Zend\Db\Sql\Where;

/**
 * Montagem de Select
 * 
 * @category   NwBase
 * @package    NwBase\Db
 * @subpackage Sql 
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class Select extends Zend_Select
{
    /**
     * Create where clause
     * Foi Alterado para aceitar o object Predicate, antes da string da coluna
     *
     * @param Where|\Closure|string|array $predicate   Where clause
     * @param string                      $combination One of the OP_* constants from Predicate\PredicateSet
     * 
     * @see Zend\Db\Sql.Select::where()
     * @return Select
     */
    public function where($predicate, $combination = Predicate\PredicateSet::OP_AND)
    {
        if ($predicate instanceof Where) {
            $this->where = $predicate;
        } elseif ($predicate instanceof Predicate\PredicateInterface) {
            $this->where->addPredicate($predicate, $combination);
        } elseif ($predicate instanceof \Closure) {
            $predicate($this->where);
        } else {
            if (is_string($predicate)) {
                // String $predicate should be passed as an expression
                $predicate = (strpos($predicate, Expression::PLACEHOLDER) !== false)
                    ? new Predicate\Expression($predicate) : new Predicate\Literal($predicate);
                $this->where->addPredicate($predicate, $combination);
            } elseif (is_array($predicate)) {

                foreach ($predicate as $pkey => $pvalue) {
                    // loop through predicates

                    if (is_string($pkey) && strpos($pkey, '?') !== false) {
                        // First, process strings that the abstraction replacement character ?
                        // as an Expression predicate
                        $predicate = new Predicate\Expression($pkey, $pvalue);
                    } elseif ($pvalue instanceof Predicate\PredicateInterface) {
                        // Predicate type is ok
                        $predicate = $pvalue;
                    } elseif (is_string($pkey)) {
                        // Otherwise, if still a string, do something intelligent with the PHP type provided

                        if ($pvalue === null) {
                            // map PHP null to SQL IS NULL expression
                            $predicate = new Predicate\IsNull($pkey, $pvalue);
                        } elseif (is_array($pvalue)) {
                            // if the value is an array, assume IN() is desired
                            $predicate = new Predicate\In($pkey, $pvalue);
                        } else {
                            // otherwise assume that array('foo' => 'bar') means "foo" = 'bar'
                            $predicate = new Predicate\Operator($pkey, Predicate\Operator::OP_EQ, $pvalue);
                        }
                    } else {
                        // must be an array of expressions (with int-indexed array)
                        $predicate = (strpos($pvalue, Expression::PLACEHOLDER) !== false)
                            ? new Predicate\Expression($pvalue) : new Predicate\Literal($pvalue);
                    }
                    $this->where->addPredicate($predicate, $combination);
                }
            }
        }
        return $this;
    }
}
