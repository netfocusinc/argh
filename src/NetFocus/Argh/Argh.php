<?php
	
namespace NetFocus\Argh;

require_once 'ArghException.php';
require_once 'ArghRuleParser.php';

class Argh
{
	const KEY = 0;
	const VALUE = 1;
	const COMMAND = 2;
	const SUBCOMMAND = 3;
	const VARIABLE = 4;
	
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
	
	public $command = null;
	
	/*
	** STATIC METHODS
	*/
	
	public static function parse($argv, array $parameters, array $syntax=null)
	{
		// Play nice when $argv is a string
		if(is_string($argv)) $argv = explode(' ', $argv);
		
		return new Argh($argv, $parameters, $syntax);
	}

	
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
			
			// Set $this->command
			$command = "";
			foreach($argv as $arg)
			{
				$command .= $arg . " ";
			}
			$this->command = $command;
		}
		
		if( (!isset($parameters)) || (!is_array($parameters)) )
		{
			$this->parameters = array();
		}
		
		try
		{
			$this->parseParameters($parameters);
			//$this->parseSyntax();
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
	
	public function usageString()
	{
		$buff = "Usage: ";
		$buff .= self::argv();
		return $buff;
	}
	
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
  
  // throws an Exception of $this->parameters
  private function parseParameters(array $parameters)
  {
	  // Do not just accept given $parameters;
	  // Process them to make sure they are valid
	  
	  if( is_array($parameters) )
		{	  
	  	$this->parameters = array();
	  
			// process an array of parameters to use for parsing arguments
			foreach($parameters as $param)
			{
				/* Example $param
				[
					'name'			=>			'debug',
					'flag'			=>			'd',
					'type'			=>			'boolean',
					'required'	=>			FALSE,
					'default'		=>			FALSE,
					'text'			=>			'Enables debug mode.'
				]
				*/
				
				// Rebuild $param in a $tmp array
				$tmp = array();
				
				// Check for required elements
				if( array_key_exists('name', $param) )
				{
					$tmp['name'] = $param['name'];
				}
				else
				{
					throw new ArghException('Parameter definitions missing required name');
				}
				
				if( array_key_exists('flag', $param) )
				{
					$tmp['flag'] = $param['flag'];
				}
				
				if( array_key_exists('type', $param) )
				{
					if( in_array($param['type'], ['boolean','string']) )
					{
						$tmp['type'] = $param['type'];
					}
					else
					{
						throw new ArghException('Parameter ' . $tmp['name'] . ' has an invalid type');
					}
				}
				else
				{
					// Assign default type
					$tmp['type'] = 'boolean';
				}
				
				if( array_key_exists('required', $param) )
				{
					if($param['required'])
						$tmp['required'] = TRUE;
					else
						$tmp['required'] = FALSE;
				}
				else
				{
					// Assign default required
					$tmp['required'] = FALSE;
				}
				
				if( array_key_exists('default', $param) )
				{
					//! TODO: confirm that default value matches type of this paramter
					$tmp['default'] = $param['default'];
				}
				
				if( array_key_exists('text', $param) )
				{
					$tmp['text'] = $param['text'];
				}
				
				// Add the $tmp param to this objects $parameters array
				array_push($this->parameters, $tmp);
				
			} // END: foreach($paramters as $param)
		}
		else
		{
			throw new ArghException('parseParameters() expects an array of $parameters)');
		}


	}
  
	private function parseArguments()
	{
		// parse $this->argv using $this->syntax and $this->parameters to create a $this->arguments array
		
		// create a map of arguments
		// create a map of parameters (includes argument values)
		// create convenience maps by flag, name
		
		// create convenience properties on this object (for each parameter)
		//eg $this->{$key} = $value;
		
		// Initialize this objects $arguments array
		$this->arguments = array();
		
		// Convenience pointers
		$argv = $this->argv;
		
		for($i=1; $i<count($argv); $i++)
		{
			echo "\nDEBUG: Considering \$argv[$i] " . $argv[$i] . " ... \n";
			
			echo count($this->rules) . " rules to test\n";
			
			foreach($this->rules as $rule)
			{
				echo "DEBUG: Checking for match with rule: " . $rule['name'] . " (" . $rule['syntax'] . ")" . "\n";
				
				$tokens = array();
				if( preg_match($rule['syntax'], $argv[$i], $tokens) )
				{
					echo "DEBUG: " . $argv[$i] . " matches syntax pattern " . $rule['syntax'] . "\n";
					
					// Build an argument in a $tmp array
					$tmp = array();
					
					// Loop through $matches and assign data to $arguments based on the current rules semantics
					for($j=1; $j<count($tokens); $j++)
					{
						$token = $tokens[$j];
						//echo "DEBUG: token: " . $token . "\n";
						// Semantic meaning of token for this rule
						$meaning = $rule['semantics'][$j-1];
						echo "DEBUG: token: " . $token . " (" . $meaning . ")\n";
						
						switch($meaning)
						{
							case self::KEY:
								//! TODO: Check if this 'key' matches a defined parameter 'name' or 'flag'
								// if it matches a 'flag', use the corresponding 'name' for this arguments 'key' instead
								// ? if no match, okay to assign to arguments anyway
								$tmp['key'] = $token;
								break;
							case self::VALUE:
								$tmp['value'] = $token;
								break;
							case self::COMMAND:
								break;
							case self::SUBCOMMAND:
								break;
							default:
								// ? Throw exception
						}
						
					} // END: for($j=1; $j<count($matches); $j++)
					
					//! TODO: set boolean values for flags to TRUE
					
					//! TODO: validate tmp argument before adding to this objects arguments array
					
					// Create a property on this object with the arguments name
					$this->{$tmp['key']} = $tmp['value'];
					
					// Add $tmp argument to this objects $arguments array
					array_push($this->arguments, $tmp);
					
					break; // move on to the next $argv element
					
				} // END: if( preg_match($rule->syntax, $argv[$i], $matches) )
				
				
				
			} // END: foreach($interpreter as $rule)
			
		} // END: for($i=1; $i<count($argv); $i++)
		
	}
	
}
	
?>