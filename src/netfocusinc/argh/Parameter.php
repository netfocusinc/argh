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
	protected $value;
		
	//
	// STATIC METHODS
	//
	
	public static function createWithAttributes(array $attributes): Parameter
	{
		
		// Init default attributes for a new Parameter
		$name = null;
		$flag = null;
		$required = FALSE;
		$default = null;
		$description = null;
		$options = array();
		
		// Extract parameter attributes from array
		if( array_key_exists('name', $attributes) ) $name = $attributes['name'];
		if( array_key_exists('flag', $attributes) ) $flag = $attributes['flag'];
		if( array_key_exists('required', $attributes) ) $required = $attributes['required'];
		if( array_key_exists('default', $attributes) ) $default = $attributes['default'];
		if( array_key_exists('description', $attributes) ) $description = $attributes['description'];
		if( array_key_exists('options', $attributes) ) $options = $attributes['options'];
		
		// Construct a new Parameter instance
		// Late static binding results in new instance of (calling) subclass
		
		//! TODO: What if this is called on abstract Parameter
		
		return new static($name, $flag, $required, $default, $description, $options);
	}
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct(string $name, string $flag=null, bool $required=FALSE, $default=null, string $description=null, array $options=array())
	{
		// Required a non-empty 'name'
		if(empty($name))
		{
			throw(new ArghException('Parameter must have a name'));
		}
		
		// Set properties on this object
		$this->name = $name;
		$this->flag = $flag;
		$this->required = $required;
		$this->default = $default;
		$this->description = $description;
		$this->options = $options;
		
		// New Parameters ALWAYS have null value
		$this->value = null;
	}
	
	//
	// GETTERS
	//
	
	public function getName(): string { return $this->name; }
	
	public function getFlag() { return $this->flag; }
	
	public function isRequired(): bool { return $this->required; }
	
	public function getDefault() { return $this->default; }
	
	public function getDescription() { return $this->description; }
	
	public function	getOptions(): array { return $this->options; }
	
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
		if( in_array($value, $this->options) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	//
	// ABSTRACT METHODS
	//
	
	abstract public function getParameterType(): int;
	
	abstract public function setValue($value);
	
}