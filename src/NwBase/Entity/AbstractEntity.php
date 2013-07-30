<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Entity;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use NwBase\Entity\ServiceLocatorAwareTrait;
use NwBase\Model\InterfaceModel;
use NwBase\DateTime\DateTime as NwDateTime;
use NwBase\Entity\Hydrator\HydratorEntity;

/**
 * Classe abstrata para criação de entity
 * 
 * @category NwBase
 * @package  NwBase\Entity
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @abstract
 */
abstract class AbstractEntity implements InterfaceEntity, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    /**
     * @var boolean
     */
    protected $_stored = null;
    
    /**
     * @var boolean
     */
    protected $_storedClean = null;
    
    /**
     * @var array
     */
    protected $_defaultValues = array();
    
    /**
     * @var array
     */
    protected $_modified = array();
    
    /**
     * @var HydratorInterface
     */
    protected $_hydrator = null;
    
    /**
     * Construct, recebe os dados caso seja necessario
     * 
     * @param array|object $data   Dados de Entrada Padrão
     * @param boolean      $stored Flag se os dados estaão armazenados
     */
    public function __construct($data = array(), $stored = false)
    {
        $this->_stored = (boolean) $stored;
        $this->_storedClean  = (boolean) $stored;
        
        if (count($data)) {
            $this->exchangeArray($data);
        }
    }
    
    /**
     * Retorna se a entidade esta armazenado
     *
     * @return boolean
     */
    public function getStored()
    {
        return (boolean) $this->_stored;
    }
    
    /**
     * Retorna o array  com as propriedade modificadas
     *
     * @return array
     */
    public function getModified()
    {
        return $this->_modified;
    }
    
    /**
     * Valida se uma propriedade foi alterada do valor original
     *
     * @param string $property Nome da Propriedade
     *
     * @return boolean
     */
    public function hasModified($property)
    {
        return array_key_exists($property, $this->_modified);
    }
    
    /**
     * Limpa a lista das propriedades modificadas
     *
     * @return void
     */
    public function clearModified()
    {
        $this->_modified = array();
        $this->_storedClean  = false;
        $this->_defaultValues = $this->getArrayCopy();
    }
    
    /**
     * Set todas as propriedades existente na entidade
     * 
     * @param array|object $data Dados de Entrada
     * 
     * @return InterfaceEntity
     */
    public function exchangeArray($data)
    {
        if (is_object($data) ) {
            if (is_callable(array($data, 'getArrayCopy'))) {
                $data = $data->getArrayCopy();
                
            } else {
                $data = get_object_vars($data);
            }
        }
        
        if (is_array($data) && count($data) ) {
            $this->getHydrator()->hydrate($data, $this);
        }
        
        if ($this->_stored && $this->_storedClean) {
            $this->clearModified();
        }
        
        return $this;
    }
    
    /**
     * Extrai as propriedade para um array
     * 
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->getHydrator()->extract($this);
    }
    
    /**
     * Executa o metodo getArrayCopy
     * 
     * @final 
     * @return array
     */
    final public function toArray()
    {
        return $this->getArrayCopy();
    }
    
    /**
     * Retorna os dados como uma string
     *
     * @return string
     */
    public function toString()
    {
        return '';
    }
    
    /**
     * Metodo magico para retornar a string quando o metodo é chamado como string
     *
     * @final
     * @return string
     */
    final public function __toString()
    {
        return $this->toString();
    }
    
    /**
     * Bloqueia o metodo magico para nunca utilizar, setar a propriedade somente
     * pelo metodo set[NameProperty]
     * 
     * @param string $property Name Property
     * @param mixed  $value    Valor da Propriedade
     * 
     * @final
     * @throws \InvalidArgumentException
     * @return void
     */
    final public function __set($property, $value)
    {
        $msg = sprintf('Set direto da propriedade não permitido, Utilize o metodos "set"', $property);
        throw new \InvalidArgumentException($msg);
    }
    
    /**
     * Seta o valor da propriedade
     * 
     * @param string $property Name Property
     * @param mixed  $value    Valor da Propriedade
     * 
     * @final
     * @throws \InvalidArgumentException
     * @return InterfaceEntity
     */
    final public function setProperty($property, $value)
    {
        // Valida se existe a propriedade, caso contrario gera a exceção
        if (!property_exists($this, $property)) {
            $msg = sprintf('Propriedade "%s" inválida', $property);
            throw new \InvalidArgumentException($msg);
        }
        
        // Filtra o valor, removendo os espaços e define null para string vazias
        if (is_string($value)) {
            $value = trim($value);
        }
        
        $value = $value!='' ? $value : null;
        
        $this->$property = $value;
        
        // Verifica se o valor informado para setar é diferente do atual
        // para relamente fazer a modificação
        if ($this->_stored) { 
            if (!isset($this->_defaultValues[$property]) || $this->_defaultValues[$property] != $this->$property) {
                $this->_modified[$property] = $value;
                
            } elseif (isset($this->_modified[$property])) {
                unset($this->_modified[$property]);
            }
        }
        
        return $this;
    }
    
    /**
     * Busca o valor da propriedade
     *
     * @param string $property Name Property
     *
     * @final
     * @throws \InvalidArgumentException
     * @return mixed
     */
    final public function __get($property)
    {
        // Valida se existe a propriedade, caso contrario gera a excessão
        if (!property_exists($this, $property)) {
            $msg = sprintf('Propriedade "%s" inválida', $property);
            throw new \InvalidArgumentException($msg);
        }
    
        // Veririca se existe um metodo "GET" da propriedade, e executa a mesma
        $words = array_map('ucfirst', explode("_", $property));
        $method = "get";
        $method .= implode("", $words);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
    
        return $this->$property;
    }
    
    /**
     * Metodo magico para definir os metodo set e get das propriedades pelo name
     * 
     * @param string $method Name Method
     * @param array  $args   Argumentos
     * 
     * @final
     * @throws \BadMethodCallException
     * @throws \RuntimeException
     * @return mixed
     */
    final public function __call($method, $args)
    {
        if ( ! preg_match('/^(set|get)(([A-Z]{1})([A-Za-z0-9]+))/', $method, $matches) ) {
            $msg = sprintf('Metodo "%s" inválido', $method);
            throw new \BadMethodCallException($msg);
        }
        
        $property = preg_replace('/([A-Z])/', '_\\1', $matches[2]);
        $property = trim($property, '_');
        $property = strtolower($property);
        
        switch ($matches[1]) {
            case 'set':
                $args  = (array) $args;
                $value = array_shift($args);
                
                // Verifica se existe um metodo "SET" da propriedade, 
                // e executa a mesma inves de setar manualmente o valor
                if (method_exists($this, $method)) {
                    return $this->$method($value);
                } else {
                    return $this->setProperty($property, $value);
                }
                break;
            case 'get':
                return $this->__get($property);
                break;
            default:
                throw new \RuntimeException("Erro interno na expressão regular. method __call");
                break;
        }
    }
    
    /**
     * Recebe valor de data e seu formato, e cria um objeto datetime
     * 
     * @param string          $format Formato de Data e Hora
     * @param string|DateTime $value  Valor de data e hora
     * 
     * @static
     * @return \NwBase\DateTime\DateTime
     */
    public static function valueDateTime($format, $value)
    {
        if (empty($value)) {
            return null;
        }
        
        switch ($format) {
            case NwDateTime::DATETIME:
                $nameObj = "NwBase\\DateTime\\DateTime";
                break;
            case NwDateTime::DATE:
                $nameObj = "NwBase\\DateTime\\Date";
                break;
            case NwDateTime::TIME:
                $nameObj = "NwBase\\DateTime\\Time";
                break;
            default:
                return null;
        }
        
        if ($value instanceof NwDateTime) {
            $datetime = $value;
        
        } elseif ($value instanceof \DateTime) {
            $datetime = new $nameObj();
            $datetime->setTimestamp($value->getTimestamp());
        } else {
            try {
                $datetime = new $nameObj($value);
            } catch (\Exception $e) {
                $datetime = null;
            }
        }
        
        return $datetime;
    }
    
    /**
     * Get the hydrator to use for each row object
     *
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->_hydrator) {
            $this->_hydrator = new HydratorEntity;
        }
        
        return $this->_hydrator;
    }
    
    /**
     * Executado antes da inserção da entity no database
     * 
     * @param InterfaceModel $model Model Database
     * 
     * @return void
     */
    public function preInsert(InterfaceModel $model)
    {
    }
    
    /**
     * Executado depois da inserção da entity no database
     *
     * @param InterfaceModel $model Model Database
     *
     * @return void
     */
    public function postInsert(InterfaceModel $model)
    {
    }
    
    /**
     * Executado antes da edição da entity no database
     *
     * @param InterfaceModel $model Model Database
     *
     * @return void
     */
    public function preUpdate(InterfaceModel $model)
    {
    }
    
    /**
     * Executado depois da edição da entity no database
     *
     * @param InterfaceModel $model Model Database
     *
     * @return void
     */
    public function postUpdate(InterfaceModel $model)
    {
    }
    
    /**
     * Executado antes da exclusão da entity no database
     *
     * @param InterfaceModel $model Model Database
     *
     * @return void
     */
    public function preDelete(InterfaceModel $model)
    {
    }
    
    /**
     * Executado depois da exclusão da entity no database
     *
     * @param InterfaceModel $model Model Database
     *
     * @return void
     */
    public function postDelete(InterfaceModel $model)
    {
    }
}
