<?php
	
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
	* The main class to be used by clients for parsing command line arguments
	*
	* This is the description for a DocBlock. This text may contain
	* multiple lines
	*
	* @author  Benjamin Hough <benjamin@netfocusinc.com>
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
		*
		* @api
		*
		* @since 1.0.0
		*
		* @param array $argv
		* @param array $parameters Array of Parameters
		*/
	public static function parse(array $argv, array $parameters)
	{
		$argh = new Argh($parameters);
		
		$argh->parseArguments($argv);
		
		return $argh;
	}
	
	/**
		*
		* @api
		*
		* @since 1.0.0
		*
		* @param string $args A string simulating command line entry
		* @param array $parameters Array of Parameters
		*/
	public static function parseString(string $args, array $parameters)
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
		* @since 1.0
		*		 
		* @param string $name The name (or flag) of a defined Parameter
		*
		* @return mixed The value of a Parameter (type depends on the Parameter's type)
		*
		*/	
	public function __get(string $key)
	{
		// Get parameters from this instance
		return $this->get($key);
	}
	
	/**
		* 
		*
		*
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
		* Contructs an Argh instance
		*
		* @api
		*
		* @since 1.0
		*		 
		* @param array $argv Array of cli arguments as registered by PHP CLI
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
		
		//! TODO: Replace this; Accept FLAGS argument to enable naked variables (Rule selection)
		// ... ? require client to add 'variable' parameter
		$this->parameters->addParameter(VariableParameter::createWithAttributes(
				[
					'name'	=>	Parameter::ARGH_NAME_VARIABLE
				]
			)
		);
				
	} // END: public function __construct()
	
	/**
		*
		*
		*
		*/
	public function parseArguments($argv)
	{
		// Set properties on this object
		$this->argv = $argv;
		
		try
		{	
			// Create an new ArgumentParser instance
			$parser = new ArgumentParser($this->language, $this->parameters);
			
			// Prepare $argv for parsing
			$args = ArgvPreprocessor::process($this->argv);
			
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
		* @since 1.0
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
		* @since 1.0
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
	
	public function variables()
	{
		if($this->parameters->hasVariable())
		{	
			return $this->parameters->get(Parameter::ARGH_NAME_VARIABLE)->getValue();
		}
		
		// No ARGH_TYPE_VARIABLE Parameters in ParameterCollection
		return FALSE;
	}
	
	public function parameters() { return $this->parameters; }
	
	//! TODO: Accept a formatting string/array (e.g. ['-f', '--name', 'text'])
	public function usageString()
	{
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
	
	// An alias for usageString()
	public function usage() { return $this->usageString(); }

	
}
	
?>