<?php

namespace NwBase\Validator;

use NwBase\Validator\IsHostApp;

class IsHostAppTest extends \PHPUnit_Framework_TestCase
{

	protected $_hostname;
	
	public function setUp()
	{
		$this->_hostname = new IsHostApp();
		$_SERVER['HTTP_HOST'] = 'www.localhost.com.br'; 
	}	
	
	public function testHostValid()
	{
		$host_valid = "localhost.com.br";		
		
		$valid = $this->_hostname->isValid($host_valid);
		$this->assertTrue($valid, "Deveria passar o hostname: " .$host_valid); 
	}
	
	public function testHostInvalid()
	{
		$host_invalid = "outrohost.com.br";
		
		$valid = $this->_hostname->isValid($host_invalid);
		$this->assertFalse($valid, "Deveria nao passar o hostname: " .$host_invalid);
	}
}
