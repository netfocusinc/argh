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
		$this->arguments[] = $arguments;
	}
	
	public function all()
	{
		return $this->arguments;
	}
	
}
	
?>