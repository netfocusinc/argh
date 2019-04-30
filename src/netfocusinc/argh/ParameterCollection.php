<?php

/**
	* ParameterCollection.php
	*/
		
namespace netfocusinc\argh;

use netfocusinc\argh\Argument;
use netfocusinc\argh\Parameter;

/**
	* Representation of a collection of Parameters
	*
	* A ParameterCollection maintains a list of the Parameters used to interpret command line arguments
	* In addition to maintaining the Parameter list, this class is also responsible for "merging" Arguments
	* with Parameters. This process involves using the Arguments (as parsed from the command line) to set
	* values of Parameters in the collection.
	*
	* @internal
	*
	* @author Benjamin Hough
	*
	* @since 1.0.0
	*/
class ParameterCollection
{	
	//
	// PRIVATE PROPERTIES
	//
	
	/** @var array An array of Parameters */
	private $parameters;
	
	/** @var array A map from Parameter keys to their index in the $parameters array */
	private $map;
	
	//
	// Magic Methods
	//
	
	/**
		* Magic function returns a string representation of this ParameterCollection
		*
		* @since 1.0.0
		*
		* @return string
		*/
	public function __toString()
	{
		return $this->toString();
	}
		
	//
	// PUBLIC METHODS
	//
	
	/**
		* ParameterCollection constructor
		*
		* Constructs a new ParameterCollection with an empty list of Parameters
		* and a "map" that can be used to reference Parameters in the collection by 'name' or 'tag'
		*
		* @since 1.0.0
		*/
	public function __construct()
	{
		// Create a Parameter array
		$this->parameters = array();
		
		// Create a 'map' array for quick lookup of Parameters by index
		$this->map = array();
	}
	
	/**
		* Returns TRUE when the specified $key matches the 'name' or 'flag' of a Parameter in the collection
		*
		* @since 1.0.0
		* 
		* @param string $key
		*
		* @return bool Returns TRUE when the specified $key matches a Parameter in the collection; FALSE otherwise.
		*/
	public function exists(string $key): bool
	{
		if( array_key_exists($key, $this->map) )
		{
			return true;
		}
		
		return false;
	}

	/**
		* Returns a boolean indicating if this collection contains a CommandParameter
		*
		* @since 1.0.0
		*
		* @return bool Returns TRUE when the collection contains a CommandParameter
		*/
	public function hasCommand(): bool
	{
		foreach($this->parameters as $p)
		{
			if( Parameter::ARGH_TYPE_COMMAND == $p->getParameterType() )
			{
				return true;
			}
		}
		return false;		
	}
	
	/**
		* Returns a boolean indicating if this collection contains a VariableParameter
		*
		* @since 1.0.0
		*
		* @return bool Returns TRUE when the collection contains a VariableParameter
		*/
	public function hasVariable(): bool
	{
		foreach($this->parameters as $p)
		{
			if( Parameter::ARGH_TYPE_VARIABLE == $p->getParameterType() )
			{
				return true;
			}
		}
		return false;	
	}
	
	/**
		* Retrieves a Parameter in this collection by 'name' or 'flag'
		*
		* @since 1.0.0
		*
		* @param string $key The 'name' or 'flag' of a Parameter
		* 
		* @return Parameter
		* @throws ArghException When there is no Parameter in the collection matching the specified $key
		*/
	public function get(string $key): Parameter
	{
		if($this->exists($key))
		{
			$index = $this->map[$key];
			
			return $this->parameters[$index];
		}
		else
		{
			throw new ArghException('Parameter \'' . $key . '\' not in collection');
		}	
	}
	
	/**
		* Returns an array of command strings
		*
		* If this collection contains any CommandParameters,
		* this method will return an array of the values defined by these commands.
		*
		* @since 1.0.0
		*
		* @return array
		*/
	public function getCommands(): array
	{
		$commands = array();
		
		foreach($this->parameters as $p)
		{
			if( Parameter::ARGH_TYPE_COMMAND == $p->getParameterType() )
			{
				$commands[] = $p;
			}
		}
		
		return $commands;	
	}

	/**
		* Adds a Parameter to the array of Parameters maintained by this collection.
		*
		* @since 1.0.0
		*
		* @param Parameter $param
		* 
		* @throws ArghException If a Parameter with the same 'name' already exists
		*/
	public function addParameter(Parameter $param)
	{
		if( !$this->exists($param->getName()) )
		{
			// Add $param to $parameters array
			$this->parameters[] = $param;
			
			// Map the new parameter's 'name' to its corresponding index in the $parameters array
			$this->map[$param->getName()] = count($this->parameters)-1;
			
			// Map the new parameter's 'flag' to its corresponding index in the $parameters array
			if(!empty($param->getFlag())) $this->map[$param->getFlag()] = count($this->parameters)-1;
		}
		else
		{
			throw(new ArghException(__CLASS__ . ': Parameter \'' . $param->getName() . '\' cannot be redefined.'));
		}
	}
	
	/**
		* Given an array of Arguments, this method sets the 'value' of Parameters in the collection
		* with the 'value' of its corresponding Argument.
		*
		* @since 1.0.0
		*
		* @param array $arguments An array of Arguments
		*
		* @throws ArgumentException
		*/
	public function mergeArguments(array $arguments): void
	{
		
		foreach($arguments as $a)
		{	
			// Check for a Parameter with this Arguments key
			if( $this->exists($a->getKey()) )
			{	
				// Enforce limitations of Parameters
				// 1. Do NOT allow value to be redefined
				// 2. ARGH_TYPE_VARIABLE (VariableParameter) can have values appended
				
				if( Parameter::ARGH_TYPE_VARIABLE == $this->parameters[$this->map[$a->getKey()]]->getParameterType() )
				{
					// Call VariableParameters::addValue() method
					$this->parameters[$this->map[$a->getKey()]]->addValue($a->getValue());
				}
				else if( null !== $this->parameters[$this->map[$a->getKey()]]->getValue() )
				{
					//
					// Do NOT allow a Parameter's value to be redefined
					//
					
					throw(new ArghException(__CLASS__ . ': Parameter \'' . $a->getKey() . '\' value cannot be redefined.'));
				}
				else
				{
					// Set Parameter value	
					$this->parameters[$this->map[$a->getKey()]]->setValue($a->getValue());
				}
			}
			else
			{
				throw(new ArghException(__CLASS__ . ': Cannot merge Argument \'' . $a->getKey() . '\'. Parameter not defined.'));
			}
			
		} // END: foreach($arguments as $a)
		
		// Check for REQUIRED Parameters without any value
		foreach($this->parameters as $p)
		{
			if( ($p->isRequired() ) && (null == $p->getValue()) )
			{
				throw(new ArghException(__CLASS__ . ': Missing required parameter \'' . $p->getName() .  '\'.'));
			}
		}
	}
	
	/**
		* Returns the array of Parameters in this collection.
		*
		* @since 1.0.0
		*
		* @return array Array of Parameters in this collection.
		*/
	public function all()
	{
		return $this->parameters;
	}
	
	/**
		* Returns a string representation of this ParameterCollection.
		*
		* @since 1.0.0
		*
		* @return string Returns
		*/
	public function toString()
	{
		return print_r($this->parameters, TRUE);
	}
	
}
	
?>