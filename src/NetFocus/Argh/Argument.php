<?php
	
namespace NetFocus\Argh;

class Argument
{
	
	//
	// PRIVATE PROPERTIES
	//
	
	private $key = null;
	private $value = null;
	private $type = null;
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct($key=null, $value=null, $type=null)
	{
		$this->key = $key;
		$this->value = $value;
		$this->type = $type;
	}
	
	// GETTERS/SETTERS
	
	public function key($key=null)
	{ 
		if( $key !== null)
		{
			$this->key = $key;
		}
		
		return $this->key;
	}

	public function type($type=null)
	{ 
		if( $type !== null )
		{
			$this->type = $type;
		}
		
		return $this->type;
	}	
	
	
	public function value($value=null)
	{
		if( $value !== null )
		{
			$this->value = $value;
		}
		
		return $this->value;
	}
	
	public function isArray()
	{
		if( is_array($this->value) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
}