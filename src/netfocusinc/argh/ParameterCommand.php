<?php
	
namespace netfocusinc\argh;

use netfocusinc\argh\Parameter;

/**
	* A Command Parameter.
	*
	* @since 1.0.0
	*/
class ParameterCommand extends Parameter
{
	
	//
	// STATIC FUNCTIONS
	//
	
	
	//
	// PUBLIC FUNCTIONS
	//
	
	/**
		* Construct a new ParameterCommand.
		*
		* Overrides Parameter constructor to enforce required 'options'
		*
		*
		*/
		
	public function __construct(string $name, string $flag=null, bool $required=FALSE, $default=null, string $description=null, array $options=array())
	{
		
		// Required a non-empty 'options'
		if( count($options) < 1 )
		{
			throw(new ArghException('ParameterCommand must have options'));
		}
		
		// Call Parameter (Parent) Constructor
		parent::__construct($name, $flag, $required, $default, $description, $options);
	}
	
	/**
		* Returns one of the Parameter::ARGH_TYPE's
		*
		* @since 1.0.0
		*
		* @return int
		*/
	public function getParameterType(): int
	{
		return Parameter::ARGH_TYPE_COMMAND;
	}

	/**
		* Sets the string value of this Parameter.
		*
		* @since 1.0.0
		*
		*/
	public function setValue($value)
	{		
		if(is_array($value))
		{
			throw(new ArghException('ParameterCommand values cannot be set to an array'));
		}
		
		$this->value = strval($value);
	}
	
}

?>



