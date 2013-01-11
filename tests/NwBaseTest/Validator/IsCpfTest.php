<?php

namespace NwBase\Validator;

use NwBase\Validator\IsCpf;

class IsCpfTest extends \PHPUnit_Framework_TestCase
{

	protected $_isCpf;
	
	public function setUp()
	{
		$this->_isCpf = new IsCpf();
	}	
	
	public function testCpfValid()
	{
		$cpf_valid = "055.591.061-00";		
		
		$valid = $this->_isCpf->isValid($cpf_valid);
		$this->assertTrue($valid, "Deveria passar o CPF: " .$cpf_valid); 
	}
	
	public function testCpfInvalid()
	{
		$cpf_invalid = "123.254.565-88";
		
		$valid = $this->_isCpf->isValid($cpf_invalid);
		$this->assertFalse($valid, "Deveria nao passar o CPF: " .$cpf_invalid);
	}
}
