<?php
	
namespace netfocusinc\argh;

use netfocusinc\argh\Argument;
use netfocusinc\argh\Parameter;

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
	
	public function __toString()
	{
		return $this->toString();
	}
		
	//
	// PUBLIC METHODS
	//
	
	public function __construct()
	{
		// Create a Parameter array
		$this->parameters = array();
		
		// Create a 'map' array for quick lookup of Parameters by index
		$this->map = array();
	}
	
	public function exists(string $key): bool
	{
		if( array_key_exists($key, $this->map) )
		{
			return true;
		}
		
		return false;
	}
	
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
			throw(new ArghException(__CLASS__ . ': Parameter \'' . $param->name() . '\' cannot be redefined.'));
		}
	}
	
	public function mergeArguments(array $arguments): void
	{
		
		foreach($arguments as $a)
		{	
			// Check for a Parameter with this Arguments key
			if( $this->exists($a->getKey()) )
			{	
				// Enforce limitations of Parameters
				// 1. Do NOT allow value to be redefined
				// 2. ARGH_TYPE_VARIABLE (ParameterVariable) can have values appended
				
				if( Parameter::ARGH_TYPE_VARIABLE == $this->parameters[$this->map[$a->getKey()]]->getParameterType() )
				{
					// Call ParameterVariables::addValue() method
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
	}
	
	public function all()
	{
		return $this->parameters;
	}
	
	public function toString()
	{
		return print_r($this->parameters, TRUE);
	}
	
}
	
?>