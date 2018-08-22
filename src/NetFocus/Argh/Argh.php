<?php
	
namespace NetFocus\Argh;

class Argh
{
	/*
	** CONTANTS
	*/
	
	const KEY = 0;
	const VALUE = 1;
	const COMMAND = 2;
	const SUBCOMMAND = 3;
	const VARIABLE = 4;
	
	/*
	** PRIVATE MEMBER DATA
	*/
	
	private $rules = [
		
		[
			'name'			=>	'cmd:sub variable',
			'syntax'		=>	'/^([a-z]+):([a-z]+)[\s]+([\S]+)$/i',
			'semantics'	=>	[self::COMMAND, self::SUBCOMMAND, self::VARIABLE]
		],
		
		[
			'name'			=>	'-f value',
			'syntax'		=>	'/^\-([a-z]{1})[\s]+([\S]+)$/i',
			'semantics'	=>	[self::KEY, self::VALUE]
		],
		
		[
			'name'			=>	'--key',
			'syntax'		=>	'/^\-\-([a-z_\-]+)$/i',
			'semantics'	=>	[self::KEY]
		],
		[
			'name'			=>	'--key=value',
			'syntax'		=>	'/^\-\-([a-z_\-]+)=([\S]+)$/i',
			'semantics'	=>	[self::KEY, self::VALUE]
		],
		[
			'name'			=>	'-k',
			'syntax'		=>	'/^\-([a-z]{1})$/i',
			'semantics'	=>	[self::KEY]
		],
		[
			'name'			=>	'cmd:sub',
			'syntax'		=>	'/^([a-z]+):([a-z]+)$/i',
			'semantics'	=>	[self::COMMAND, self::SUBCOMMAND]
		],
		
	];
	
	private $argv = null;
	private $parameters = null;
	private $arguments = null;
	private $map = null;
	
	/*
	** PUBLIC MEMBER DATA
	*/
	
	/*
	** STATIC METHODS
	*/
	
	public static function parse($argv, array $parameters, array $rules=null)
	{
		// Play nice when $argv is a string
		if(is_string($argv)) $argv = explode(' ', $argv);
		
		return new Argh($argv, $parameters, $rules);
	}
	
	/*
	** PROPERTY OVERLOADING
	*/
	
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
	
	/*
	** PUBLIC METHODS
	*/
	
	public function __construct(array $argv, array $parameters, array $rules=null)
	{
		/*
		** CHECK RULES
		*/
		
		// Check for defined $rules argument to override defaults
		if( isset($rules) )
		{
			if( is_array($rules) )
			{
				if( count($rules) > 0 )
				{
					// Replace this objects default rules with given rules
					$this->rules = $rules;
				}
				else
				{
					throw new ArghException('Empty rule set given');
				}
			}
			else
			{
				throw new ArghException('Expecting array \$rules, ' . gettype($rules) . ' given');
			}
		}
		
		// Parse the rules to confirm their validity
		try
		{
			ArghRuleParser::parse($this->rules);
		}
		catch(ArghException $e)
		{
			throw $e;
		}
		
		/*
		** CHECK ARGUMENTS
		*/
		
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
		
		
		try
		{
			$this->parameters = ArghParameterParser::parse($parameters);
		
			// Do NOT include $argv[0] in call to parse(); it contains the name of the cli script
			$this->arguments = ArghArgumentParser::parse(array_slice($this->argv, 1), $this->rules, $this->parameters);
			
			// Merge arguments into $parameters by key
			ArghParameterParser::merge($this->parameters, $this->arguments);
			
			// Create an index map for parameters by 'name' and 'flag'
			$this->map = ArghParameterParser::map($this->parameters);
			
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
	
	/*
  ** PRIVATE METHODS
  */

	
}
	
?>