<?php

/**
	* StringParameter.php
	*/
	
namespace netfocusinc\argh;

use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;


/**
	* String parameter
	*
	* Subtype of Parameter that represents a string value.
	*
	* @api
	*
	* @author Benjamin Hough
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
		* Returns ARGH_TYPE_STRING
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
		* @param mixed $value
		*
		* @throws ArghExpception If $value is an array.
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