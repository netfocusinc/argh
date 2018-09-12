<?php
	
namespace NetFocus\Argh;

class Argh
{
		
	//
	// PRIVATE PROPERTIES
	//
	
	private $argv = null;
	private $language = null; // Language
	private $parameters = null; // array of Parameters
	private $arguments = null; // array of Arguments
	private $map = null;
	
	//
	// PUBLIC PROPERTIES
	//
	
	//
	// STATIC METHODS
	//
	
	public static function parse($argv, array $params)
	{
		// Play nice when $argv is a string
		if(is_string($argv))
		{
			// Force string into an array
			$argv = explode(' ', $argv);
			
			// Prepend a placeholder element at index 0; this will be removed by constructor
			// This mimics PHP's $argv[0] that is registered as the name of the CLI script
			array_unshift($argv, 'garbage');
		}
		
		// Create a new Parameters instance
		$parameters = new Parameters();
		
		// Add Parameters for each elements defined in $params array
		foreach($params as $p)
		{
			try
			{
				$parameters->addParameter(Parameter::createFromArray($p));
			}
			catch(Exception $e)
			{
				throw($e);
			}
		}
		
		return new Argh($argv, $parameters);
	}
	
	//
	// PROPERTY OVERLOADING
	//
	
	//public void __set ( string $name , mixed $value )
	
	public function __get(string $name)
	{
		if(isset($this->{$name}))
		{
			return $this->{$name};
		}
		else
		{
			// Get parameters from this object
			return $this->get($name);
		}
	}
	
	//public bool __isset ( string $name )
	//public void __unset ( string $name )
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct(array $argv, Parameters $parameters)
	{
		//
		// LANGUAGE (RULES)
		//
				
		try
		{
			// Get a reference to the Language (singleton instance)
			$this->language = Language::instance();
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		//
		// CHECK ARGUMENTS
		//
		
		if(!isset($argv))
		{
			throw new ArghException('Missing required \$argv argument');
		}
		else if(!is_array($argv))
		{
			throw new ArghException('Expecting array \$argv, ' . gettype($argv) . ' given');
		}
		else
		{
			$this->argv = $argv;
		}
		
		//
		// PARSE PARAMETERS AND ARGUMENTS
		//
		
		try
		{
			$this->parameters = $parameters;
			
			// Prepare $argv for parsing
			$args = ArgvPreprocessor::process($this->argv);
			
			$this->arguments = ArgumentParser::parse($args, $this->language, $this->parameters);
			
			// Merge arguments into $parameters by key
			//ArghParameterParser::merge($this->parameters, $this->arguments);
			//! TODO: ? Merging NOT necessary any more, add methods on Argh to lookup a Parameters value based on existing Arguments
			
			// Create an index map for parameters by 'name' and 'flag'
			//$this->map = ParameterMapper::map($this->parameters);
			//! TODO: ? Mapping NOT necessary any more, add methods on Parameters to lookup a Parameter by key
			//! OR: Create a ParameterMapper and Parameter->get($i) methods
			
		}
		catch(ArghException $e)
		{
			throw $e;
		}		
	}
	
	public function command()
	{
		return implode(' ', $this->argv);
	}
	
	// Returns the value of an argument supplied on command line, or the default value from parameter definition
	public function get($name)
	{
		// check the map for parameter with name or flag =$name
		if( array_key_exists($name, $this->map) )
		{
			// retrieve the matching parameters index from the map
			$i = $this->map[$name];
			
			// retrieve the value of the parameter, or the default, or null
			if( array_key_exists('argument', $this->parameters[$i]) )
			{
				if( array_key_exists('value', $this->parameters[$i]['argument']) )
				{
					return $this->parameters[$i]['argument']['value'];
				}
				else
				{
					throw new ArghException(__METHOD__ . ': Argument \'' . $name .  '\' has no value.'); 
				}
			}
			else if( array_key_exists('default', $this->parameters[$i]) )
			{
				return $this->parameters[$i]['default'];
			}
			else
			{
				return null;
			}
		}
		else
		{
			throw new ArghException('Parameter \'' . $name . '\' was not defined.');
		}
	}
	
	// Returns the definition of a parameter by name
	public function param($name)
	{
	}
	
	public function parametersString()
	{
		return print_r($this->parameters, TRUE);
	}
	
	public function argumentsString()
	{
		return print_r($this->arguments, TRUE);
	}
	
	public function mapString()
	{
		return print_r($this->map, TRUE);
	}
	
	public function debugString()
	{
	}
	
	public function usageString()
	{
		$buff = "Usage: ";
		$buff .= self::argv();
		return $buff;
	}
	
	//! TODO: Accept a formatting string/array (e.g. ['-f', '--name', 'text'])
	// An alias for usageString()
	public function usage() { return self::usageString(); }
	
	// Returns elements of the $this->argv array
	public function argv(int $i=0)
	{
		return $this->argv[$i];
	}

	
}
	
?>