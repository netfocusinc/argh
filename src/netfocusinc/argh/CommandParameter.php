<?php

/**
	* CommandParameter.php
	*/
	
namespace netfocusinc\argh;

use netfocusinc\argh\Parameter;

/**
	* A Command Parameter.
	*
	* Subtype of Parameter that represents a command string
	* Command parameters must define an array of 'options'.
	* When interpreting command line arguments, commands will only be matched with command line input
	* when the command line argument matches one of the pre-defined 'options'.
	*
	* @author Benjamin Hough
	*
	* @api
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
		* @since 1.0.0
		*
		* @param string $name
		* @param string $flag
		* @param bool $required
		* @param string $default The default 'option' to use for this command, when none is specified on the command line
		* @param string $description
		* @param array $options
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
		* Returns ARGH_TYPE_COMMAND
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
		* Checks that the given 'value' matches one of this Commands 'options' before settings its value.
		*
		* @since 1.0.0
		*
		* @param string $value
		*
		* @throws ArghException When $value is not one of this CommandsParameter's 'options'
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
