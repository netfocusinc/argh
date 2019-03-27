<?php
	
/**
	* BooleanParameter.php
	*/
	
namespace netfocusinc\argh;

use netfocusinc\argh\Parameter;

/**
	* Boolean parameter.
	*
	* Subtype of Parameter that represents a boolean value.
	* Boolean parameters values are restricted to boolean literal values.
	*
	* @api
	*
	* @author Benjamin Hough
	*
	* @since 1.0.0
	*/
class BooleanParameter extends Parameter
{
	
	//
	// STATIC FUNCTIONS
	//
	
	
	//
	// PUBLIC FUNCTIONS
	//

	/**
		* Returns a boolean default value for this Parameter.
		*
		* Overrides Parameter::getDefault() to ensure a boolean return value
		*
		* @since 1.0.0
		*
		* @return bool
		*/	
	public function getDefault()
	{
		// Interpret any non-empty value as TRUE
		// Note that FALSE is considered to be empty()
		if( !empty( parent::getDefault() ) )
		{
			return TRUE;
		}	
		else
		{
			return FALSE;
		}
	}
	
	/**
		* Returns ARGH_TYPE_BOOLEAN
		*
		* @since 1.0.0
		*
		* @return int
		*/
	public function getParameterType(): int
	{
		return Parameter::ARGH_TYPE_BOOLEAN;
	}

	/**
		* Sets the boolean value of this Parameter.
		*
		* Translates any non boolean values to their boolean equivalents.
		*
		* @since 1.0.0
		*
		* @return int
		*/
	public function setValue($value)
	{
				
		if( null === $value )
		{
			// null (no value) considered TRUE (this is how flags work; e.g. the presence of a boolean flag -x without value means TRUE)
			$this->value = TRUE;
		}
		else if( FALSE == $value )
		{
			// 'Falsey' (boolean) FALSE, (int) 0, (float 0.0), (string) '0', (string) '', NULL
			$this->value = FALSE;
		}
		else if( in_array($value, array('0', 'false', 'False', 'FALSE', 'off', 'Off', 'OFF')) )
		{
			// Certain character values should be considered to mean FALSE
			$this->value = FALSE;
		}
		else
		{
			// Everything else considered TRUE
			$this->value = TRUE;
		}
	}
	
}

?>