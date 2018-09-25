<?php
	
namespace netfocusinc\argh;

class Argument
{
	
	//
	// PRIVATE PROPERTIES
	//
	
	private $key = null;
	private $value = null;
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct($key=null, $value=null)
	{
		$this->key = $key;
		$this->value = $value;
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