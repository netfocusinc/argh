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
			if( Parameter::ARGH_TYPE_COMMAND == $p->type() )
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
			if( Parameter::ARGH_TYPE_VARIABLE == $p->type() )
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
			if( Parameter::ARGH_TYPE_COMMAND == $p->type() )
			{
				$commands[] = $p;
			}
		}
		
		return $commands;	
	}

	public function addParameter(Parameter $param)
	{
		if( !$this->exists($param->name()) )
		{
			// Add $param to $parameters array
			$this->parameters[] = $param;
			
			// Map the new parameter's 'name' to its corresponding index in the $parameters array
			$this->map[$param->name()] = count($this->parameters)-1;
			
			// Map the new parameter's 'flag' to its corresponding index in the $parameters array
			if(!empty($param->flag())) $this->map[$param->flag()] = count($this->parameters)-1;
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
			if( $this->exists($a->key()) )
			{
				//! TODO: Enforce limitations of Parameters
				// 1. Do NOT allow value to be redefined
				// 2. For ARGH_TYPE_VARIABLE, append values to any already existing
				
				if( Parameter::ARGH_TYPE_VARIABLE == $this->parameters[$this->map[$a->key()]]->type() )
				{
					//
					// For ARGH_TYPE_VARIABLE, append values to any already existing
					// Note: Argument value will always be an array
					
					// Get existing 'value' array from ARGH_TYPE_VARIABLE Parameter
					$value = $this->parameters[$this->map[$a->key()]]->value();
					
					if($value === null)
					{
						// No variables have been set yet
						$value = $a->value();						
					}
					else if(is_array($value))
					{
						// Add new values to existint array
						foreach($a->value() as $e) $value[] = $e;
					}
					
					// Update the Parameter's value in the collection
					$this->parameters[$this->map[$a->key()]]->setValue($value);
				}
				else if( null !== $this->parameters[$this->map[$a->key()]]->value() )
				{
					//
					// Do NOT allow a Parameter's value to be redefined
					//
					
					throw(new ArghException(__CLASS__ . ': Parameter \'' . $a->key() . '\' value cannot be redefined.'));
				}
				else
				{
					// Set Parameter value	
					$this->parameters[$this->map[$a->key()]]->setValue($a->value());
				}
			}
			else
			{
				throw(new ArghException(__CLASS__ . ': Cannot merge Argument \'' . $a->key() . '\'. Parameter not defined.'));
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