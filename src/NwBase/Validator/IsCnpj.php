<?php

namespace NwBase\Validator;

use Zend\Validator\AbstractValidator;

class IsCnpj extends AbstractValidator
{
    const INVALID = 'cpfInvalid';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::INVALID   => "Cnpj '%value%' inválido!",
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
            case '00000000000000':
            case '11111111111111':
            case '22222222222222':
            case '33333333333333':
            case '44444444444444':
            case '55555555555555':
            case '66666666666666':
            case '77777777777777':
            case '88888888888888':
            case '99999999999999':
                $valid = false;
                break;
        }
        
        if ( strlen($value) != 14 ) {
            $valid = false;
        }
        
        if ( $valid === true ) {
            $c = substr($value, 0, 12);
            $dv = substr($value, 12, 2);
            $_dOne = 0;
            for ($i = 0; $i < 12; $i++) {
                $_dOne += substr($c, (11-$i), 1)*(2+($i % 8));
            }
            
            if ($_dOne == 0) {
                $valid = false;
            }
                
            $_dOne = 11 - ($_dOne % 11);
            
            if ($_dOne > 9) {
                $_dOne = 0;
            }
                
            if (substr($dv, 0, 1) != $_dOne) {
                $valid = false;
            }
            
            $_dOne *= 2;
            for ($i = 0; $i < 12; $i++) {
                $_dOne += substr($c, (11-$i), 1)*(2+(($i+1) % 8));
            }
            
            $_dOne = 11 - ($_dOne % 11);
            if ($_dOne > 9) {
                $_dOne = 0;
            }
            
            if (substr($dv, 1, 1) != $_dOne) {
                $valid = false;
            }
        }
        
        if ($valid == false) {
            $this->error(self::INVALID);
        }
        
        return $valid;
    }
}
