<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause
 * @package   NwBase\Filter
 */
namespace NwBase\Validator;

use Zend\Validator\Db\NoRecordExists;

/**
 * Filtrar, removendo os caracteres adicionais, padrão espaço removendo no meio e no inicio e fim
 *
 * @category NwBase
 * @package  NwBase\Filter
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @author   Edson Junior <edson@naturalweb.com.br>
 */
class UniqueField extends NoRecordExists
{
    /**
     * Valida se um campo é unique na tabela tirando seu id primary
     * 
     * @param mixed $value   Valor a Valida
     * @param array $context Valores dos campos
     * 
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if ($this->exclude !== null && is_array($this->exclude)) {
            if (isset($this->exclude['primary_field'])) {
                
                $primary_field = $this->exclude['primary_field'];
                $value_exclude = '';
                
                if (($context !== null) && isset($context) && array_key_exists($primary_field, $context)) {
                    $value_exclude = $context[$primary_field];
                }
                
                $this->exclude['value'] = $value_exclude;
            }
        }
        
        return parent::isValid($value);
    }
}
