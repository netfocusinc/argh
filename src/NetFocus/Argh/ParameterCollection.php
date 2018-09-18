<?php
	
namespace NetFocus\Argh;

class ParameterCollection
{	
	//
	// PRIVATE PROPERTIES
	//
	
	private $parameters = null; // (array) Parameter
		
	//
	// PUBLIC METHODS
	//
	
	public function __construct()
	{
		// Create a Parameter array
		$this->parameters = array();
	}
	
	public function exists(string $key): bool
	{
		foreach($this->parameters as $p)
		{
			if( ($key == $p->name()) || ($key == $p->flag()) )
			{
				return true;
			}
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
	
	public function get(string $key)
	{
		foreach($this->parameters as $p)
		{
			if( ($key == $p->name()) || ($key == $p->flag()) )
			{
				return $p;
			}
		}
		
		throw new ArghException('Parameter \'' . $key . '\' not in collection');	
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
		$this->parameters[] = $param;
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