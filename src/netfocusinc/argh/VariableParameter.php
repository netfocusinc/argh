<?php
	
/**
	* VariableParameter.php
	*/
	
namespace netfocusinc\argh;

use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;

/**
	* Variable parameter.
	*
	* Subtype of Parameter that represents an (unmarked) variable.
	* Variable Parameters are used to save unmakred input (naked variables)
	* Their value always consists of an array.
	*
	* @api
	*
	* @author Benjamin Hough
	*
	* @since 1.0.0
	*/
class VariableParameter extends Parameter
{
	
	//
	// STATIC FUNCTIONS
	//
	
	/**
		* Returns an instance of VariableParameter
		*
		* VariableParameters are always named with the ARGH_NAME_VARIABLE constant
		*
		* @since 1.0.1
		*
		* @return Parameter
		*/
	public static function create() : Parameter
	{	
		return parent::createWithAttributes(
			[
				'name' => Parameter::ARGH_NAME_VARIABLE,
				'required' => FALSE,
				'description' => 'Unnamed argument inputs' 
			]
		);
	}
	
	/**
		* Returns an instance of VariableParameter
		*
		* Overriding Parameter::createWithAttribute() here prevents this from being called with a custom 'name' attribute
		* VariableParameters need to be named with ARGH_NAME_VARIABLE constant; Argh uses this 'name' to find variables
		*
		* @since 1.0.2
		*
		* @param array $attributes
		*
		* @return Parameter
		*/
	public static function createWithAttributes(array $attributes) : Parameter
	{	
		// Force VariableParameters to use a constant name
		if(array_key_exists('name', $attributes))
		{
			$attributes['name'] = Parameter::ARGH_NAME_VARIABLE;
		}
		
		return parent::createWithAttributes($attributes);
	}
	
	//
	// PUBLIC FUNCTIONS
	//
	
	/**
		* Returns ARGH_TYPE_VARIABLE
		*
		* @since 1.0.0
		*
		* @return int
		*/
	public function getParameterType(): int
	{
		return Parameter::ARGH_TYPE_VARIABLE;
	}

	/**
		* Sets the array value of this Parameter.
		*
		* Forces all values into an array
		*
		* @since 1.0.0
		*
		* @param mixed $value
		*/
	public function setValue($value)
	{	
		if(is_array($value))
		{
			$this->value = $value;
		}
		else
		{
			$this->value = array($value);
		}
	}

	/**
		* Adds an element to the value of this Parameter
		*
		* Forces all values into an array
		*
		* @since 1.0.0
		*
		* @param mixed $value
		*
		*/	
	public function addValue($value)
	{
		// Check if this Parameter has a previously set value
		if($this->value === null)
		{
			// Initialize this Parameters value to a new array
			$this->value = array();
		}
		
		// Check if the new value is an array
		if(!is_array($value))
		{
			// Append new single value to this Parameters value array
			$this->value[] = $value;
		}
		else
		{
			// Append every new value to this Parameters value array
			foreach($value as $v) $this->value[] = $v;
		}
	}
	
}

?>