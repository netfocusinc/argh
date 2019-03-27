<?php

/**
	* Parameter.php
	*/

namespace netfocusinc\argh;

/**
	* Parameters define the arguments your CLI application can recieve.
	*
	* Parameters are pre-configured arguments that your CLI application can retrieve
	* from command line arguments.
	*
	* @api
	*
	* @author  Benjamin Hough
	*
	* @since 1.0.0
	*/
abstract class Parameter
{
	
	//
	// CONSTANTS
	//
	
	// Parameter Names
	const ARGH_NAME_VARIABLE	= '_ARGH_VARIABLE_';
	
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
	
	/**
		* Creates a new Parameter (sub-type) using the provided attributes
		*
		* This function is called statically on the subtypes of Parameter (e.g. BooleanParameter)
		* It uses the supplied attributes to construct a new Parameter.
		*
		* @api
		*
		* @since 1.0.0
		*
		* @param array $attributes
		*
		* @return Parameter
		* @throws ArghException
		*/
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
	
	/**
		* Parameter contructor.
		*
		* This function defines a constructor that is leveraged by Parameter sub-types.
		* Normally, Parameter (sub-types, e.g. BooleanParameter) are creating using the static Parameter:createWithAttributes() function.
		* Parameter is an abstract class, and as such cannot be instantiated directly.
		*
		* @since 1.0.0
		*
		* @param string $name
		* @param string $flag
		* @param bool $required
		* @param mixed $default
		* @param string $description
		* @param array $options
		*
		* @return Parameter
		* @throws ArghException
		*/
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
	
	/**
		* Returns the text 'name' of this Parameter
		*
		* @since 1.0.0
		*
		* @return string
		*/
	public function getName(): string { return $this->name; }
	
	/**
		* Returns the character 'flag' of this Parameter
		*
		* @since 1.0.0
		*
		* @return string
		*/
	public function getFlag() { return $this->flag; }
	
	/**
		* Returns the a boolean value indicating if this Parameter is required
		*
		* @since 1.0.0
		*
		* @return boolean
		*/
	public function isRequired(): bool { return $this->required; }
	
	/**
		* Returns the 'default' value of this Parmater
		*
		* @since 1.0.0
		*
		* @return mixed
		*/
	public function getDefault() { return $this->default; }
	
	/**
		* Returns the text 'description' of this Parameter
		*
		* @since 1.0.0
		*
		* @return string
		*/
	public function getDescription() { return $this->description; }

	/**
		* Returns an array of 'options' that are legal for this Parameters 'value'
		*
		* @since 1.0.0
		*
		* @return array
		*/
	public function	getOptions(): array { return $this->options; }
	
	/**
		* Returns a boolean indicating if this Parameter has any defined 'options'
		*
		* @since 1.0.0
		*
		* @return bool
		*/
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
	
	/**
		* Returns a boolean indicating if the specified $value is a permissible 'option' of this Parameter
		*
		* @since 1.0.0
		*
		* @param mixed $value
		* 
		* @return bool
		*/
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

	/**
		* Returns the 'value' of this Parameter
		*
		* @since 1.0.0
		*
		* @return mixed
		*/	
	public function getValue()
	{
		return $this->value;
	}
	
	//
	// ABSTRACT METHODS
	//
	
	/**
		* Returns an int corresponding to this Parameters type
		*
		* @since 1.0.0
		*
		* @return int
		*/
	abstract public function getParameterType(): int;
	
	/**
		* Sets the 'value' of this Paramter
		*
		* @since 1.0.0
		*
		* @param mixed $value
		*/
	abstract public function setValue($value);
	
}

?>