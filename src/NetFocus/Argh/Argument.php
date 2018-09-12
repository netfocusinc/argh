<?php
	
namespace NetFocus\Argh;

class Argument
{
	
	public key = null;
	public value = null;
	
	//
	// PUBLIC METHODS
	//
	
	public function __contruct($key, $value)
	{
		$this->key = $key;
		$this->value = $value;
	}
	
	public function key() { return $this->key; }
	
	public function value() { return $this->value; }
	
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