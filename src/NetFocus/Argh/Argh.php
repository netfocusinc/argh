<?php
	
namespace NetFocus\Argh;

//
// CONVENIENCE DEFINITIONS
//

define('ARGH_TYPE_BOOLEAN', Parameter::ARGH_TYPE_BOOLEAN, true);
define('ARGH_TYPE_INT', Parameter::ARGH_TYPE_INT, true);
define('ARGH_TYPE_STRING', Parameter::ARGH_TYPE_STRING, true);
define('ARGH_TYPE_LIST', Parameter::ARGH_TYPE_LIST, true);

class Argh
{
		
	//
	// PRIVATE PROPERTIES
	//
	
	private $argv = null;
	private $language = null; // Language
	private $parameters = null; // ParameterCollection
	private $arguments = null; // ArgumentCollection
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
		
		// Create a new ParameterCollection instance
		$parameters = new ParameterCollection();
		
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
			// Get parameters from this instance
			return $this->get($name);
		}
	}
	
	//public bool __isset ( string $name )
	//public void __unset ( string $name )
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct(array $argv, ParameterCollection $parameters)
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
		
		// DEBUG
		foreach($parameters->all() as $p)
		{
			echo "DEBUG: Parameter: (name: " . $p->name() . ") (flag: " . $p->flag() . ")\n";
		}
		
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
	
	public function argv(int $i=null)
	{
		if($i !== null)
		{
			if( $i < count($this->argv) )
			{
				return $this->argv[$i];
			}
			else
			{
				throw new ArghException('Invalid index for argv');
			}
		}
		else
		{
			return $this->argv;
		}
	}
	
	public function command()
	{
		return implode(' ', $this->argv);
	}
	
	// Returns the value of an argument supplied on command line, or the default value from parameter definition
	public function get($key)
	{
		
		// Check the ParameterCollection for a Parameter with $key
		if( !$this->parameters->exists($key) )
		{
			throw new ArghException('Parameter \'' . $name . '\' was not defined.');
		}
		
		// Check the ArgumentCollection for an Argument with this $key
		if( $this->arguments->exists($key) )
		{
			// Return the Arguments value
			return $this->arguments->get($key)->value();
		}
		else
		{
			// Return the Parameters default value, if any
			return $this->parameters->get($key)->default();
		}
	}
	
	public function parameters() { return $this->parameters; }
	
	public function arguments() { return $this->arguments; }
	
	public function parametersString()
	{
		return print_r($this->parameters, TRUE);
	}
	
	public function argumentsString()
	{
		return print_r($this->arguments, TRUE);
	}
	
	//! TODO: Accept a formatting string/array (e.g. ['-f', '--name', 'text'])
	public function usageString()
	{
		$buff = 'Usage: ';
		$buff .= $this->argv(0) . "\n";
	
		// TODO: Sort the parameters by name
		
		// TODO: Determine the longest value of each parameter attribute, to make pretty columns
		
		foreach($this->parameters->all() as $p)
		{
			$buff .= '-' . $p->flag() . "\t" . $p->name() . "\t" . $p->text() . "\n";
		}
		
		return $buff;
	}
	
	// An alias for usageString()
	public function usage() { return $this->usageString(); }

	
}
	
?>