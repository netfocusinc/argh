<?php
	
namespace NetFocus\Argh;

/**
	* Parameters define the arguments your CLI application can recieve.
	*
	* Parameters are pre-configured arguments that your CLI application can retrieve
	* from command line arguments.
	*
	* @author  Benjamin Hough <benjamin@netfocusinc.com>
	*
	*/
class Parameter
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
	private $name = null;

	/** @var string A single character flag used to refer to this Parameter. */
	private $flag = null;
	
	private $type = null;
	private $required = null;
	private $default = null;
	private $text = null;
	private $options = null;
		
	//
	// STATIC METHODS
	//
	
	public static function createFromArray(array $attributes): Parameter
	{		
		// Init defaults
		$name = null; // required
		$flag = ''; // default
		$type = Parameter::ARGH_TYPE_BOOLEAN; // default
		$required = FALSE; // default
		$default = FALSE; // default
		$text = ''; // default
		$options = array(); // default
		
		// Extract parameter attributes from array
		if( array_key_exists('name', $attributes) ) $name = $attributes['name'];
		if( array_key_exists('flag', $attributes) ) $flag = $attributes['flag'];
		if( array_key_exists('type', $attributes) ) $type = $attributes['type'];
		if( array_key_exists('required', $attributes) ) $required = $attributes['required'];
		if( array_key_exists('default', $attributes) ) $default = $attributes['default'];
		if( array_key_exists('text', $attributes) ) $text = $attributes['text'];
		if( array_key_exists('options', $attributes) ) $options = $attributes['options'];
		
		// Check for required 'name'
		if(empty($name)) throw(new ArghException(__METHOD__ . ': $attributes missing required \'name\''));
		
		// Check for required 'options' for ARGH_TYPE_COMMANDs
		if( (Parameter::ARGH_TYPE_COMMAND==$type) && ( (empty($options)) || (!is_array($options)) || (count($options)<1)  ) )
		{
			throw(new ArghException(__METHOD__ . ': Command parameter \'' . $name . '\' missing required \'options.\''));
		}
		
		// Return a new Parameter instance
		return new self($name, $flag, $type, $required, $default, $text, $options);

	}
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct(string $name, string $flag, int $type=self::ARGH_TYPE_BOOLEAN, bool $required=FALSE, $default=null, string $text='', array $options=null)
	{	

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
		
		// Set properties on this object
		$this->name = $name;
		$this->flag = $flag;
		$this->type = $type;
		$this->required = $required;
		$this->default = $default;
		$this->text = $text;
		$this->options = $options;
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
	
}