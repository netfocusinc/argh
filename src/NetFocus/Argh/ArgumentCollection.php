<?php
	
namespace NetFocus\Argh;

class ArgumentCollection
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
		// If an argument with the same 'key' already exists, throw an exception
		if( $this->exists($argument->key()) )
		{
			throw new ArghException('Argument with key \'' . $argument->key() . '\' already exists');
		}
		
		$this->arguments[] = $argument;
	}
	
	public function exists(string $key): bool
	{
		foreach($this->arguments as $a)
		{
			if( $key == $a->key() )
			{
				return true;
			}
		}
		return false;
	}
	
	public function get(string $key)
	{
		foreach($this->arguments as $a)
		{
			if( $key == $a->key() )
			{
				return $a;
			}
		}
		
		throw new ArghException('Argument \'' . $key . '\' not in collection');	
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