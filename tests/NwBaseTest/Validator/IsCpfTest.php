<?php

namespace NwBase\Validator;

use NwBase\Validator\IsCpf;

class IsCpfTest extends \PHPUnit_Framework_TestCase
{
	public function testCpfValid()
	{
		$cpf_valid = "055.591.061-00";		
		
		$isCpf = new IsCpf();
		$valid = $isCpf->isValid($cpf_valid);
		$this->assertTrue($valid, "Deveria passar o CPF: " .$cpf_valid); 
	}
	
	public function testCpfInvalid()
	{
		$cpf_invalid = "123.254.565-88";
		
		$isCpf = new IsCpf();
		$valid = $isCpf->isValid($cpf_invalid);
		$this->assertFalse($valid, "Deveria nao passar o CPF: " .$cpf_invalid);
		
		$msgs = $isCpf->getMessages();
		$this->assertEquals($msgs[$isCpf::INVALID_CPF], sprintf("Cpf '%s' inválido!", $cpf_invalid));
	}
	
	public function testVariosCpfInvalidos()
	{
	    $isCnpj = new IsCpf();
	
	    $this->assertFalse($isCnpj->isValid("12345678901234"));
	    $this->assertFalse($isCnpj->isValid("00000000000"));
	}
}
