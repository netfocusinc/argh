<?php
	
namespace netfocusinc\argh;

/**
	* Parameters define the arguments your CLI application can recieve.
	*
	* Parameters are pre-configured arguments that your CLI application can retrieve
	* from command line arguments.
	*
	* @author  Benjamin Hough <benjamin@netfocusinc.com>
	*
	* @since 1.0.0
	*
	*/
abstract class Parameter
{
	
	//
	// CONSTANTS
	//
	
	// Parameter Names
	const ARGH_NAME_VARIABLE	= '_ARGH_VARS_';
	
	// Parameter Data Types
	const ARGH_TYPE_BOOLEAN		= 1;
	const ARGH_TYPE_INT				= 2;
	const ARGH_TYPE_STRING		= 3;
	const ARGH_TYPE_LIST			= 4;
	const ARGH_TYPE_COMMAND		= 5;
	const ARGH_TYPE_VARIABLE	= 6;
	
	//
	// PUBLIC PROPERTIES
	//
	
	/** @var string The name of a Parameter. Can be used on the command line; e.g. -file  */
	private $name;

	/** @var string A single character flag used to refer to this Parameter. */
	private $flag;
	
	/** @var int One of the Parameter data types. */
	private $type;
	
	/** @var bool Is the Parameter required? */
	private $required;
	
	/** @var mixed The default value of the Parameter */
	private $default;
	
	/** @var string Descriptive text to print with 'usage' */
	private $description;
	
	/** @var array A list limiting the options for this Parameters value */
	private $options;
	
	/** 
		* @var mixed The value of this parameter.
		*
		* Null indicates no Argument was supplied on the command line.
		* 
		* Each Parameter may have its own type of value (e.g. int, string, array)
		*/
	private $value;
		
	//
	// STATIC METHODS
	//
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct(string $name, string $flag=null, bool $required=FALSE, $default=null, string $text=null, array $options=null)
	{	

		/*
		// Check for valid $type
		$valid = array(self::ARGH_TYPE_BOOLEAN, self::ARGH_TYPE_INT, self::ARGH_TYPE_STRING, self::ARGH_TYPE_LIST, self::ARGH_TYPE_COMMAND, self::ARGH_TYPE_VARIABLE);
		
		if( !in_array($type, $valid) )
		{
			throw new ArghException('Parameter \'' . $name . '\' has an invalid type');
		}
		
		// Check ARGH_TYPE_COMMAND for required options
		if(self::ARGH_TYPE_COMMAND == $type)
		{
			if( count($options) == 0 )
			{
				throw new ArghException(__METHOD__ . ': Command parameter missing required: \'options\'');
			}
		}
		
		// Check for optional $required
		if( !empty($required) )
		{
			// Assign literal TRUE
			$required = TRUE;
		}
		
		// Check default value for booleans
		if(self::ARGH_TYPE_BOOLEAN == $type)
		{
			// Interpret any non-empty value as TRUE
			if( !empty($default) )
			{
				// Assign literal TRUE for boolean defaults
				$default = TRUE;
			}	
			else
			{
				$default = FALSE;
			}
		}
		*/
		
		
		
		// Set properties on this object
		$this->name = $name;
		$this->flag = $flag;
		$this->type = $type;
		$this->required = $required;
		$this->default = $default;
		$this->text = $text;
		$this->options = $options;
		$this->value = null;
	}
	
	//
	// GETTERS
	//
	
	public function name(): string { return $this->name; }
	
	public function flag() { return $this->flag; }
	
	public function type(): int { return $this->type; }
	
	public function required(): bool { return $this->required; }
	
	public function default() { return $this->default; }
	
	public function text() { return $this->text; }
	
	public function	options(): array { return $this->options; }
	
	public function value() { return $this->value; }
	
	public function hasOptions(): bool
	{
		if(count($this->options) > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function isOption($value): bool
	{
		if($this->hasOptions())
		{
			if( in_array($value, $this->options) )
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	public function setValue($value): void
	{
		switch($this->type)
		{
			case ARGH_TYPE_BOOLEAN:
			
				if(!is_bool($value))
				{
					// Convert value to boolean
					$value = boolval($value);
				}
				
				break;
			
			case ARGH_TYPE_INT:
			
				if(!is_int($value))
				{
					if(is_numeric($value))
					{
						// Convert value to int
						$value = intval($value);
					}
					else
					{
						throw(new ArghException(__CLASS__ . ': Parameter \'' . $this->name . '\' expects int value, \'' . gettype($value) . '\' given.'));
					}
				}
				
				break;
			
			case ARGH_TYPE_STRING:
			
				if(!is_string($value))
				{
					// Convert value to string
					$value = strval($value);
				}
				
				break;
			
			case ARGH_TYPE_LIST:
			
				if(!is_array($value))
				{
					// Force $value into an array
					$value = array($value);
				}
				
				break;
			
			case ARGH_TYPE_COMMAND:
			
				if(!is_string($value))
				{
					// Convert value to string
					$value = strval($value);
				}
				
				// Confirm valid option
				if( !$this->isOption($value) )
				{
					throw(new ArghException(__CLASS__ . ': Invalid option for \'' . $this->name . '\'.'));
				}
				
				break;
			
			case ARGH_TYPE_VARIABLE:
			
				// Can contain any type of parameter value
				// including string or array

				break;

		} // END: switch($this->type)
		
		// Set this objects 'value' property
		$this->value = $value;
		
	} // END: function setValue()
	
}