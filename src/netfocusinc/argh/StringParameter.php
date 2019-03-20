<?php
	
namespace netfocusinc\argh;

use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;


/**
	* A String Parameter.
	*
	* @since 1.0.0
	*/
class StringParameter extends Parameter
{
	
	//
	// STATIC FUNCTIONS
	//
	
	//
	// PUBLIC FUNCTIONS
	//
	
	/**
		* Returns one of the Parameter::ARGH_TYPE's
		*
		* @since 1.0.0
		*
		* @return int
		*/
	public function getParameterType(): int
	{
		return Parameter::ARGH_TYPE_STRING;
	}

	/**
		* Sets the string value of this Parameter.
		*
		* Casts all values to string.
		*
		* @since 1.0.0
		*
		* @return int
		*/
	public function setValue($value)
	{		
		if(is_array($value))
		{
			throw(new ArghException('StringParameter values cannot be set to an array'));
		}
		
		$this->value = strval($value);
	}
	
}

?>