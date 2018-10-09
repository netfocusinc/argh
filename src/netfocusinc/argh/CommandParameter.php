<?php
	
namespace netfocusinc\argh;

use netfocusinc\argh\Parameter;

/**
	* A Command Parameter.
	*
	* @since 1.0.0
	*/
class CommandParameter extends Parameter
{
	
	//
	// STATIC FUNCTIONS
	//
	
	
	//
	// PUBLIC FUNCTIONS
	//
	
	/**
		* Construct a new CommandParameter.
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
			throw(new ArghException('CommandParameter must have options'));
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
			throw(new ArghException('Command value cannot be set to an array'));
		}
		
		if( ($this->hasOptions()) && (!$this->isOption($value)) )
		{
			throw(new ArghException('Not a valid option for \'' . $this->getName() . '\''));
		}
		
		$this->value = strval($value);
	}
	
}

?>



