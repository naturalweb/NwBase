<?php
namespace NwBaseTest\Validator;

use NwBase\Validator\UniqueField;
use \ArrayObject;

class UniqueFieldTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Return a Mock object for a Db result with rows
	 *
	 * @return \Zend\Db\Adapter\Adapter
	 */
	protected function getMockHasResult()
	{
		// mock the adapter, driver, and parts
		$mockConnection = $this->getMock('Zend\Db\Adapter\Driver\ConnectionInterface');
	
		// Mock has result
		$mockHasResultRow      = new ArrayObject();
		$mockHasResultRow->one = 'one';
	
		$mockHasResult = $this->getMock('Zend\Db\Adapter\Driver\ResultInterface');
		$mockHasResult->expects($this->any())
					->method('current')
					->will($this->returnValue($mockHasResultRow));
	
		$mockHasResultStatement = $this->getMock('Zend\Db\Adapter\Driver\StatementInterface');
		$mockHasResultStatement->expects($this->any())
							->method('execute')
							->will($this->returnValue($mockHasResult));
	
		$mockHasResultDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
		$mockHasResultDriver->expects($this->any())
							->method('createStatement')
							->will($this->returnValue($mockHasResultStatement));
		$mockHasResultDriver->expects($this->any())
							->method('getConnection')
							->will($this->returnValue($mockConnection));
	
		return $this->getMock('Zend\Db\Adapter\Adapter', null, array($mockHasResultDriver));
	}
	
	/**
	 * Return a Mock object for a Db result without rows
	 *
	 * @return \Zend\Db\Adapter\Adapter
	 */
	protected function getMockNoResult()
	{
		// mock the adapter, driver, and parts
		$mockConnection = $this->getMock('Zend\Db\Adapter\Driver\ConnectionInterface');
	
		$mockNoResult = $this->getMock('Zend\Db\Adapter\Driver\ResultInterface');
		$mockNoResult->expects($this->any())
					->method('current')
					->will($this->returnValue(null));
	
		$mockNoResultStatement = $this->getMock('Zend\Db\Adapter\Driver\StatementInterface');
		$mockNoResultStatement->expects($this->any())
							->method('execute')
							->will($this->returnValue($mockNoResult));
	
		$mockNoResultDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
		$mockNoResultDriver->expects($this->any())
							->method('createStatement')
							->will($this->returnValue($mockNoResultStatement));
		$mockNoResultDriver->expects($this->any())
							->method('getConnection')
							->will($this->returnValue($mockConnection));
	
		return $this->getMock('Zend\Db\Adapter\Adapter', null, array($mockNoResultDriver));
	}
	
	/**
	 * Test the exclusion function
	 *
	 * @return void
	 */
	public function testExcludeWithArrayFieldExclude()
	{
		$validator = new UniqueField('users', 'field1', array('field' => 'id', 'primary_field' => 'field2'),
				$this->getMockHasResult());
		
		$context = array('field2' => 1);
		$this->assertFalse($validator->isValid('value3', $context));
	}
	
	/**
	 * Test the exclusion function
	 * with an array
	 *
	 * @return void
	 */
	public function testExcludeWithArrayNoRecord()
	{
		$validator = new UniqueField('users', 'field1', array('field' => 'id', 'primary_field' => 'field2'),
				$this->getMockNoResult());
		$context = array('field2' => 1);
		$this->assertTrue($validator->isValid('nosuchvalue', $context));
	}
}
