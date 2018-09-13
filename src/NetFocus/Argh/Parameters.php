<?php
	
namespace NetFocus\Argh;

class Parameters
{	
	//
	// PRIVATE PROPERTIES
	//
	
	private $parameters = null; // (array) Parameter
		
	//
	// PUBLIC METHODS
	//
	
	public function __construct()
	{
		// Create a Parameter array
		$this->parameters = array();
	}

	public function addParameter(Parameter $param)
	{
		$this->parameters[] = $param;
	}
	
	public function all()
	{
		return $this->parameters;
	}
	
	public function toString()
	{
		return print_r($this->parameters, TRUE);
	}
	
}
	
?>