<?php
namespace NwBase\Validator;

use Zend\Validator\Db\NoRecordExists;

class UniqueField extends NoRecordExists
{
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
