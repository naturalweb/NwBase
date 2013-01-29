<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright  Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license    BSD-3-Clause
 * @package    NwBase\Validator
 */
namespace NwBase\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Efetua a validação de documento CPF
 *
 * @category NwBase
 * @package  NwBase\Validator
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class IsCpf extends AbstractValidator
{
    const INVALID = 'cpfInvalid';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID   => "Cpf '%value%' inválido!",
    );
    
    /**
     * Metodo que faz a validação
     *
     * @param string $value Valor
     * 
     * @see Zend_Validate_Interface::isValid()
     * 
     * @return boolean
     */
    public function isValid($value)
    {
        $valid = true;
        $this->setValue($value);
        $value = preg_replace('/[^0-9]/', '', $value);
        
        switch($value){
            case '00000000000':
            case '11111111111':
            case '22222222222':
            case '33333333333':
            case '44444444444':
            case '55555555555':
            case '66666666666':
            case '77777777777':
            case '88888888888':
            case '99999999999':
                $this->error(self::INVALID);
                $valid = false;
                break;
        }
        
        if ( strlen($value) != 11 ) {
            $this->error(self::INVALID);
            $valid = false;
        }
        
        if ( $valid===true ) {
            for ($i = 9; $i < 11; $i++) {
                for ($d = 0, $c = 0; $c < $i; $c++) {
                    $d += $value{$c} * (($i + 1) - $c);
                }
    
                $d = ((10 * $d) % 11) % 10;
    
                if ($value{$c} != $d) {
                    $this->error(self::INVALID);
                    $valid = false;
                }
            }
        }
        
        return $valid;
    }
}
