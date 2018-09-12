<?php
	
namespace NetFocus\Argh;

// Syntax Contants
define('ARGH_TYPE_BOOLEAN', 0);
define('ARGH_TYPE_STRING', 1);
define('ARGH_TYPE_LIST', 2);

class Parameter
{
	
	//
	// PUBLIC PROPERTIES
	//
	
	public $name = null;
	public $flag = null;
	public $type = ARGH_TYPE_BOOLEAN;
	public $required = FALSE;
	public $default = null;
	public $text = null;
	
	//
	// STATIC METHODS
	//
	
	public static function createFromArray(array $p)
	{
		try
		{
			if( !is_array($p) )
			{
				throw new ArghException(__METHOD__ . ' Expecting array \$p ' . gettype($p) .' given');
			}
			
			$name = null;
			$flag = null;
			$type = null;
			$required = null;
			$default = null;
			$text = null;
			
			if( array_key_exists('name', $p) ) $name = $p['name'];
			if( array_key_exists('flag', $p) ) $name = $p['flag'];
			if( array_key_exists('type', $p) ) $name = $p['type'];
			if( array_key_exists('required', $p) ) $name = $p['required'];
			if( array_key_exists('default', $p) ) $name = $p['default'];
			if( array_key_exists('text', $p) ) $name = $p['text'];
			
			return new Parameter($name, $flag, $type, $required, $default, $text);
		}
		catch(Exception $e)
		{
			throw($e);
		}
	}
	
	//
	// PUBLIC METHODS
	//
	
	public function __contruct($name, $flag=null, $type=ARGH_TYPE_BOOLEAN, $required=FALSE, $default=null, $text=null)
	{
		
		// Check for required $name
		if( empty($name) )
		{
			throw new ArghException('Parameter is missing required name');
		}
		
		// Check for optional $type
		if( !empty($type) )
		{
			// Check for valid $type
			if( !in_array($type, [ARGH_TYPE_BOOLEAN, ARGH_TYPE_STRING, ARGH_TYPE_LIST]) )
			{
				throw new ArghException('Parameter \'' . $name . '\' has an invalid type');
			}
		}
		
		// Check for optional $required
		if( !empty($required) )
		{
			// Assign literal TRUE
			$required = TRUE;
		}
		
		// Check for optional $default
		if( !empty($defaut) )
		{
			if(ARGH_TYPE_BOOLEAN == $type)
			{
				// Assign literal TRUE for boolean defaults
				$default = TRUE;
			}	
		}
		else
		{
			if(ARGH_TYPE_BOOLEAN == $type)
			{
				// Assign literal FALSE for boolean defaults
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
	}
	
	public function name() { return $this->name; }
	
	public function flag() { return $this->flag; }
	
	public function type() { return $this->type; }
	
	public function required() { return $this->required; }
	
	public function default() { return $this->default; }
	
	public function text() { return $this->text; }
	
}