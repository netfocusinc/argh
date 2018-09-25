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
		* Factory construction of an Argh instance.
		*
		* Use this static factory function to create a new Argh instance
		* given an $argv array and and array of $params
		*
		*	@api
		* @since 1.0
		*		 
		* @param array $argv Array of cli arguments as registered by PHP CLI
		* @param array $params Multidimensional array defining the attributes of Parameters
		*		each element should contain a nested array with elements for a Parameters attributes
		*		e.g. $params[ 0 => ['name'=>'debug', 'type'=>ARGH_TYPE_BOOLEAN, 'default'=>FALSE] ]
		*
		*
		* @return Argh An instance of Argh
		*
		*/
	public static function parse(array $argv, array $params)
	{	
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
				throw(new ArghException(__CLASS__ . ': ' . $e->getMessage()));
			}
		}
		
		return new Argh($argv, $parameters);
	}
	
	//
	// MAGIC METHODS
	//
	
	//public void __set ( string $name , mixed $value )

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
		if(isset($this->{$key}))
		{
			return $this->{$key};
		}
		else
		{
			// Get parameters from this instance
			return $this->get($key);
		}
	}
	
	//public bool __isset ( string $name )
	//public void __unset ( string $name )
	
	//
	// PUBLIC METHODS
	//

	/**
		* Contructs an Argh instance
		*
		* Processes an $argv array against defined ParameterCollection
		*
		* @since 1.0
		*		 
		* @param array $argv Array of cli arguments as registered by PHP CLI
		* @param ParameterCollection $parameters A collection of parameters used to interpret command line arguments
		*
		*/
	public function __construct(array $argv, ParameterCollection $parameters)
	{
		// Set properties on this object
		$this->argv = $argv;
		$this->parameters = $parameters;
		
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
		// AUTO ADD ARGH_TYPE_VARIABLE PARAMETER
		//
		
		try
		{			
			// Create a new Parameter for ARGH_TYPE_VARIABLE
			$this->parameters->addParameter(Parameter::createFromArray(
					[
						'name'			=>	Parameter::ARGH_NAME_VARIABLE,
						'type'			=>	Parameter::ARGH_TYPE_VARIABLE
					]
				)
			);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		//
		// PARSE PARAMETERS AND ARGUMENTS
		//
		
		try
		{
			// Prepare $argv for parsing
			$args = ArgvPreprocessor::process($this->argv);
			
			// Create an new ArgumentCollection instance
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
			
	} // END: public function __construct(array $argv, ParameterCollection $parameters)

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
	
	public function command()
	{
		return implode(' ', $this->argv);
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
		if( $this->parameters->get($key)->value() )
		{
			// Return the Parameters value
			return $this->parameters->get($key)->value();
		}
		else
		{
			// Return the Parameters default value, if any
			return $this->parameters->get($key)->default();
		}
	}
	
	public function variables()
	{
		if($this->parameters->hasVariable())
		{	
			return $this->arguments->get(Parameter::ARGH_NAME_VARIABLE)->value();
		}
		
		// No ARGH_TYPE_VARIABLE Parameters in ParameterCollection
		return FALSE;
	}
	
	public function parameters() { return $this->parameters; }
	
	public function arguments() { return $this->arguments; }
	
	public function parametersString()
	{
		return print_r($this->parameters, TRUE);
	}
	
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
			if($p->type() == ARGH_TYPE_COMMAND)
			{
				$buff .= 'COMMANDS:' . "\n";
				
				if($p->hasOptions())
				{
					foreach($p->options() as $o)
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
			if( ($p->type() != ARGH_TYPE_COMMAND) && ($p->type() != ARGH_TYPE_VARIABLE) )
			{
				$buff .= '-' . $p->flag() . "\t" . $p->name() . "\t" . $p->text();
				
				if($p->hasOptions())
				{ 
					$buff .= "\t" . '[';
					foreach($p->options() as $o)
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