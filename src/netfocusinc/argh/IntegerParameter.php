<?php
	
namespace netfocusinc\argh;

use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;


/**
	* A Integer Parameter.
	*
	* @since 1.0.0
	*/
class IntegerParameter extends Parameter
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
		return Parameter::ARGH_TYPE_INT;
	}

	/**
		* Sets the int value of this Parameter.
		*
		* Casts all values to int.
		*
		* @since 1.0.0
		*
		* @return int
		*/
	public function setValue($value)
	{		
		if(!is_numeric($value))
		{
			throw(new ArghException('IntegerParameter values must be numeric'));
		}
		
		$this->value = intval($value);
	}
	
}

?>