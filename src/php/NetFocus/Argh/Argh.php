<?php
	
namespace argh;

class Argh
{
	const KEY = 0;
	const VALUE = 1;
	const COMMAND = 2;
	const SUBCOMMAND = 3;
	
	private $syntax = array([
		
		[
			'name'			=>	'--key',
			'syntax'		=>	'/^\-\-([a-z_\-]+)$/i',
			'semantics'	=>	[KEY]
		],
		[
			'name'			=>	'--key=value',
			'syntax'		=>	'/^\-\-([a-z_\-]+)=(.*)$/i',
			'semantics'	=>	[KEY, VALUE]
		],
		[
			'name'			=>	'-k',
			'syntax'		=>	'/^\-\-([a-z]{1})$/i',
			'semantics'	=>	[KEY]
		],
		[
			'name'			=>	'cmd:sub',
			'syntax'		=>	'/^([a-z]+):([a-z]+)$/i',
			'semantics'	=>	[COMMAND, SUBCOMMAND]
		],
		
	]);
	
	private $argv = null;
	private $parameters = null;
	
	private $arguments = null;
	
	/*
	** STATIC METHODS
	*/
	
	public static function parse(array $argv, array $parameters, array $syntax=null)
	{
		return new Argh($argv, $parameters, $syntax);
	}
	
	/*
	** PUBLIC METHODS
	*/
	
	public function __contructor(array $argv, array $parameters, array $syntax=null)
	{
		/*
		** CHECK ARGUMENTS
		*/
		
		if(!isset($argv))
		{
			throw new ArghException('Missing required \$argv argument');
		}
		else if(!is_array($argv))
		{
			throw new ArghException('Expecting array \$argv, ' . gettype($argv) . ' found');
		}
		else
		{
			$this->argv = $argv;
		}
		
		if( (!isset($parameters)) || (!is_array($parameters)) )
		{
			$this->parameters = array();
		}
		else
		{
			$this->parameters = $parameters;
		}
		
		try
		{
			$this->parseParameters();
			$this->parseArguments();
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	// Returns the value of an argument supplied on command line, or the default value from parameter definition
	public function get($name)
	{
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
	
	public function debugString()
	{
	}
	
	/*
  ** PRIVATE METHODS
  */
  
  // throws an Exception of $this->parameters
  private function parseParameters()
  {
	  
	}
  
	private function parseArguments()
	{
		// parse $this->arguments array
		
		// create a map of arguments
		// create a map of parameters (includes argument values)
		// create convenience maps by flag, name

		
		// create convenience properties on this object (for each parameter)
		//eg $this->{$key} = $value;
	}
	
}
	
?>