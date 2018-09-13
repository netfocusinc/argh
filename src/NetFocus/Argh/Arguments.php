<?php
	
namespace NetFocus\Argh;

class Arguments
{	
	//
	// PRIVATE PROPERTIES
	//
	
	private $arguments = null; // (array) Argument
		
	//
	// PUBLIC METHODS
	//
	
	public function __construct()
	{
		// Create a Argument array
		$this->arguments = array();
	}
	
	public function addArgument(Argument $argument)
	{
		//! TODO: If an argument with the same 'key' already exists, throw an exception
		
		$this->arguments[] = $argument;
	}
	
	public function all()
	{
		return $this->arguments;
	}
	
	public function toString()
	{
		return print_r($this->arguments, TRUE);
	}
	
}
	
?>