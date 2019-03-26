<?php
	
/**
	* Defines the Argh class
	*
	* @author  Benjamin Hough - Net Focus, Inc.
	*
	* @since 1.0.0
	*/ 
	
namespace netfocusinc\argh;

//
// CONVENIENCE DEFINITIONS
//

define('ARGH_TYPE_BOOLEAN', Parameter::ARGH_TYPE_BOOLEAN, true);
define('ARGH_TYPE_INT', Parameter::ARGH_TYPE_INT, true);
define('ARGH_TYPE_STRING', Parameter::ARGH_TYPE_STRING, true);
define('ARGH_TYPE_LIST', Parameter::ARGH_TYPE_LIST, true);
define('ARGH_TYPE_COMMAND', Parameter::ARGH_TYPE_COMMAND, true);
define('ARGH_TYPE_VARIABLE', Parameter::ARGH_TYPE_VARIABLE, true);

/**
	* Argument Helper
	* 
	* The main class to be used by clients for parsing command line arguments
	*
	* @api
	*
	* @author  Benjamin Hough - Net Focus, Inc.
	*
	* @since 1.0.0
	*
	*/
class Argh
{
	
	//
	// PRIVATE PROPERTIES
	//
	
	/** @var string A copy of the $argv array, as registered by PHP CLI */
	private $argv = null;
	
	/** @var Language  */
	private $language = null; // Language
	
	/** @var ParameterCollection  */
	private $parameters = null;
	
	//
	// STATIC METHODS
	//
	
	/**
		* Create a new Argh instance and parses the arguments from $argv array
		*
		* This convenience method accepts both the PHP $argv array, and an array of Parameters used to interpret them.
		* After creating a new instance of Argh, the $argv array is interpreted against the specificed Parameters.
		*
		* @api
		*
		* @since 1.0.0
		*
		* @param array $argv
		* @param array $parameters Array of Parameters
		*
		* @return Argh
		*/
	public static function parse(array $argv, array $parameters) : Argh
	{
		$argh = new Argh($parameters);
		
		$argh->parseArguments($argv);
		
		return $argh;
	}
	
	/**
		* Create a new Argh instance and parses the arguments from $args string
		*
		* This convenience method accepts both a string of command line arguments, and an array of Parameters used to interpret them.
		* After creating a new instance of Argh, the $args string is interpreted against the specified Parameters.
		* 
		* @api
		*
		* @since 1.0.0
		*
		* @param string $args A string simulating command line entry
		* @param array $parameters Array of Parameters
		*
		* @return Argh
		*/
	public static function parseString(string $args, array $parameters) : Argh
	{
		// Force $args into an array
		$argv = explode(' ', $args);
		
		// Create a new Argh instance
		$argh = new Argh($parameters);
		
		// Parse
		$argh->parseArguments($argv);
		
		return $argh;
	}
	
	//
	// MAGIC METHODS
	//

	/** 
		* Magic method providing access to parameter values via object properties syntax
		*
		* Forward requests for undefined object properties to Argh->get() method
		*
		*	@internal
		* @since 1.0.0
		*		 
		* @param string $name The name (or flag) of a defined Parameter
		*
		* @return mixed The value of a Parameter (type depends on the Parameter's type)
		*/	
	public function __get(string $key)
	{
		// Get parameters from this instance
		return $this->get($key);
	}
	
	/**
		* Magic method checks if a parameter value has been set via object property syntax
		*
		* @internal
		* @since 1.0.0
		*
		* @param string $key The name (of flag) of a parameter
		*
		* @return boolean TRUE when the named parameter exists, otherwise FALSE
		*/
	public function __isset(string $key): bool
	{
		if( $this->parameters->exists($key) )
		{
			return TRUE;		
		}
		else
		{
			return FALSE;
		}
	}
	
	//
	// PUBLIC METHODS
	//

	/**
		* Contructs a new Argh instance
		*
		* Constructs a new instance of Argh with the specified Parameters.
		* The resulting Argh instance is ready to interpret command line arguments.
		*
		* @api
		*
		* @since 1.0.0
		*
		* @param array $parameters An array of Parameters to use for interpreting command line arguments
		*
		*/
	public function __construct(array $parameters)
	{ 
		// Init Language
		$this->language = Language::createWithRules();
		
		// Init ParameterCollection
		$this->parameters = new ParameterCollection();
		
		// Add Parameters to the ParameterCollection
		foreach($parameters as $p)
		{
			$this->parameters->addParameter($p);
		}
				
	} // END: public function __construct()
	
	/**
		* Interprets an array of command line arguments
		* 
		* Parses the given $argv array using this instances pre-defined set of Parameters
		*
		* @api
		*
		* @since 1.0.0
		*
		* @param array $argv An array of command line arguments to interpret
		*
		* @throws ArghException
		*/
	public function parseArguments(array $argv)
	{
		// Set properties on this object
		$this->argv = $argv;
		
		try
		{	
			// Prepare $argv for parsing
			$args = ArgvPreprocessor::process($this->argv);
			
			// Create an new ArgumentParser instance
			$parser = new ArgumentParser($this->language, $this->parameters);
			
			// Parse $args into an array of Arguments
			$arguments = $parser->parse($args);
			
			// Merge Arguments into Parameters
			$this->parameters->mergeArguments($arguments);
		}
		catch(ArghException $e)
		{
			throw $e;
		}
	}

	/**
		* Access elements of the original $argv array
		*
		* Provides access to the $argv array (and its elements) as registered by PHP CLI
		*
		*	@api
		*
		* @since 1.0.0
		*		 
		* @param int|null $i The index of an $argv element; or null to return the entire $argv array
		*
		* @return mixed The value of an element of the $argv array; or the entire $argv array (when param $i is null)
		* @throws ArghException if $i is not a valid index of $argv
		*/	
	public function argv(int $i=null)
	{
		if($i !== null)
		{
			if( array_key_exists($i, $this->argv) )
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
	
	/**
		* Retrieves the value of a defined Parameter.
		*
		* Find the value of an Parameter
		* Either the value of an Argument, if supplied on the command line,
		* or, the default value as defined by the arguments corresponding Parameter.
		*
		*	@api
		*
		* @since 1.0.0
		*		 
		* @param string $key The name (or flag) of a defined Parameter
		*
		* @return mixed The value of a Parameter (type depends on the Parameter's type)
		* @throws ArghException if the $key is not the name of a defined Parameter.
		*/
	public function get($key)
	{
		
		// Check the ParameterCollection for a Parameter with $key
		if( !$this->parameters->exists($key) )
		{
			throw new ArghException(__CLASS__ . ': Parameter \'' . $key . '\' was not defined.');
		}
	
		// Check if the Parameter has a value defined by an Argument
		
		//! TODO: Replace with Parameter::getValueOrDefault()
		
		if( $this->parameters->get($key)->getValue() )
		{
			// Return the Parameters value
			return $this->parameters->get($key)->getValue();
		}
		else
		{
			// Return the Parameters default value, if any
			return $this->parameters->get($key)->getDefault();
		}
	}

	/**
		* Retrieves any (unmarked) variables that were supplied as command line arguments.
		*
		* Variables are a type of Parameter that are unmarked (they have no name or flag).
		* To parse variables, a VariableParameter must be added when creating a new Argh instance.
		* This function is used to retrieve unmarked variables supplied as command line arguments.
		* Any variables will be returned as an array of values.
		*
		*	@api
		*
		* @since 1.0.0
		*		 
		* @param string $key The name (or flag) of a defined Parameter
		*
		* @return mixed An array of strings when variables are present, otherwise FALSE
		*/	
	public function variables()
	{
		if($this->parameters->hasVariable())
		{	
			return $this->parameters->get(Parameter::ARGH_NAME_VARIABLE)->getValue();
		}
		
		// No ARGH_TYPE_VARIABLE Parameters in ParameterCollection
		return FALSE;
	}
	
	/**
		* Retrieves the ParameterCollection maintained by this Argh instance.
		*
		* Variables are a type of Parameter that are unmarked (they have no name or flag).
		* To parse variables, a VariableParameter must be added when creating a new Argh instance.
		* This function is used to retrieve unmarked variables supplied as command line arguments.
		* Any variables will be returned as an array of values.
		*
		*	@api
		*
		* @since 1.0.0
		*		 
		* @param string $key The name (or flag) of a defined Parameter
		*
		* @return ParameterCollection A collection of Parameters maintained by Argh
		*/	
	public function parameters() : ParameterCollection { return $this->parameters; }

	/**
		* Creates a 'usage string' useful for describing the command line arguments accepted by your program.
		*
		* This method can be used to create a string of descriptive text that details the command line arguments your 
		* program accepts. This can be displayed to users when your program is invoked with a 'help' flag, or 
		* when invalid command line arguments are used.
		*
		*	@api
		*
		* @since 1.0.0
		*		 
		*
		* @return string Descriptive text that details the command line arguments accepted by your program.
		*/	
	public function usageString()
	{
		//! TODO: Accept a formatting string/array (e.g. ['-f', '--name', 'text'])
		
		$buff = 'Usage: ';
		$buff .= $this->argv(0) . "\n\n";
	
		// TODO: Sort the parameters by name
		
		// TODO: Determine the longest value of each parameter attribute, to make pretty columns
		
		// Show Commands
		foreach($this->parameters->all() as $p)
		{
			if($p->getParameterType() == ARGH_TYPE_COMMAND)
			{
				$buff .= 'COMMANDS:' . "\n";
				
				if($p->hasOptions())
				{
					foreach($p->getOptions() as $o)
					{
							$buff .= $o . "\n";
					} // END: foreach($p->options() as $o)
				} // END: if($p->hasOptions())
			} // END: if($p->type() == ARGH_TYPE_COMMAND)
		} // END: foreach($this->parameters->all() as $p)
		$buff .= "\n";
		
		$buff .= 'OPTIONS:' . "\n";
		foreach($this->parameters->all() as $p)
		{
			if( ($p->getParameterType() != ARGH_TYPE_COMMAND) && ($p->getParameterType() != ARGH_TYPE_VARIABLE) )
			{
				$buff .= '-' . $p->getFlag() . "\t" . $p->getName() . "\t" . $p->getDescription();
				
				if($p->hasOptions())
				{ 
					$buff .= "\t" . '[';
					foreach($p->getOptions() as $o)
					{
						$buff .= $o . ', ';
					}
					$buff = substr($buff, 0, -2); // remove trailing ', '
					$buff .= ']';
				}
				
				$buff .= "\n";
			}

		}
		
		return $buff;
	}
	
	/**
		* An alias of function usage()
		*
		*	@api
		*
		* @since 1.0.0
		*
		* @return string Descriptive text that details the command line arguments accepted by your program.
		*/	
	public function usage() { return $this->usageString(); }
	
}
	
?>