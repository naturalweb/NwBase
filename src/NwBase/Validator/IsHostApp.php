<?php
namespace NwBase\Validator;

use Zend\Validator\AbstractValidator;
use Zend\View\Helper\ServerUrl;

/**
 * Valida se o endereco enviado é do hostname valido
 * e se é o hostname da applicação
 *
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @since    1.0
 */
class IsHostApp extends AbstractValidator
{
    const INVALID = 'hostInvalid';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID   => "Hostname de acesso invalido!",
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
        $value = trim($value, "/");
        $this->setValue($value);
        
        $helperServerUrl = new ServerUrl();
        $host = $helperServerUrl->getHost();
        
        $paternSearch = "^(https?:\/\/)?(www\.)?(.*)";
        if (preg_match("/{$paternSearch}/", $host, $match)) {
        	$host = trim($match[3], "/");
        }
        
        $patern = "^(https?:\/\/)?(www\.)?{$host}";
        $valid = preg_match("/{$patern}/", $value);
        
        return (boolean) $valid;
    }
}
