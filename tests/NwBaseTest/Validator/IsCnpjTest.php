<?php

namespace NwBaseTest\Validator;

use NwBase\Validator\IsCnpj;

class IsCnpjTest extends \PHPUnit_Framework_TestCase
{
	protected $_isCnpj;
	
	public function setUp()
    {
    	$this->_isCnpj = new IsCnpj();        
    }
    
    public function testCpnjValid()
    {
        $cnpj_valido = "63.563.726/0001-52";
        
        $isCnpj = new IsCnpj();
        $valid = $isCnpj->isValid($cnpj_valido);
        $this->assertTrue($valid, "Deveria passar o CNPJ " . $cnpj_valido);
    }
    
    public function testCpnjInvalid()
    {
        $cnpj_invalido = "11222333000112";
    
        $isCnpj = new IsCnpj();
        $valid = $isCnpj->isValid($cnpj_invalido);
        $this->assertFalse($valid, "Deveria não passar o CNPJ " . $cnpj_invalido);
    }
}
