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
	
	public function getKey()
	{ 
		return $this->key;
	}
	
	public function getValue()
	{	
		return $this->value;
	}
	
	public function setKey(string $key)
	{ 
		$this->key = $key;
	}
	
	public function setValue(string $value)
	{
		$this->value = $value;
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