<?php
	
namespace NetFocus\Argh;

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
	
	private $name = null;
	private $flag = null;
	private $type = null;
	private $required = null;
	private $default = null;
	private $text = null;
	private $options = null;
		
	//
	// STATIC METHODS
	//
	
	public static function createFromArray(array $p): Parameter
	{
		
		try
		{	
			// Defaults
			$name = null;
			$flag = null;
			$type = self::ARGH_TYPE_BOOLEAN;
			$required = FALSE;
			$default = FALSE;
			$text = null;
			$options = null;
			
			if( array_key_exists('name', $p) ) $name = $p['name'];
			if( array_key_exists('flag', $p) ) $flag = $p['flag'];
			if( array_key_exists('type', $p) ) $type = $p['type'];
			if( array_key_exists('required', $p) ) $required = $p['required'];
			if( array_key_exists('default', $p) && (!empty($p['default']))) $default = $p['default'];
			if( array_key_exists('text', $p) ) $text = $p['text'];
			if( array_key_exists('options', $p) ) $options = $p['options'];
			
			// Create a new Parameter instance
			// Throws an Exception if arguments are invalid
			$parameter = new self($name, $flag, $type, $required, $default, $text, $options);
			
			return $parameter;
		}
		catch(Exception $e)
		{
			throw($e);
		}
	}
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct(string $name, string $flag=null, int $type=self::ARGH_TYPE_BOOLEAN, bool $required=FALSE, bool $default=FALSE, string $text=null, array $options=null)
	{
		
		//echo "new Parameter(name=$name, flag=$flag, type=$type, required=$required, default=$default, text=$text)\n";
		
		// Check for required $name
		if( empty($name) )
		{
			throw new ArghException('Parameter is missing required name');
		}
		
		// Check for optional $type
		if( !empty($type) )
		{
			// Check for valid $type
			$valid = array(self::ARGH_TYPE_BOOLEAN, self::ARGH_TYPE_INT, self::ARGH_TYPE_STRING, self::ARGH_TYPE_LIST, self::ARGH_TYPE_COMMAND, self::ARGH_TYPE_VARIABLE);
			if( !in_array($type, $valid) )
			{
				throw new ArghException('Parameter \'' . $name . '\' has an invalid type');
			}
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