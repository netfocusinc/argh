<?php

/**
	* Argument.php
	*/
	
namespace netfocusinc\argh;

/**
	* Representation of a command line argument
	*
	* This class is used during the parsing process.
	* The ArgumentParser interprets command line arguments, and creating Arguments as they are parsed.
	* The resulting Arguments are them 'merged' with Parameters, assigning their values to their corresponding Parameters.
	*
	* @author Benjamin Hough
	*
	* @since 1.0.0
	*
	*/
class Argument
{
	
	//
	// PRIVATE PROPERTIES
	//
	
	/** @var string Text that uniquly identifies this Argument */ 
	private $key = null;
	
	/** @var mixed Data that represents this Arguments value */
	private $value = null;
	
	//
	// PUBLIC METHODS
	//
	
	/**
		* Contructs a new Argument with the specified key and value
		*
		* @since 1.0.0
		*/
	public function __construct($key=null, $value=null)
	{
		$this->key = $key;
		$this->value = $value;
	}
	
	// GETTERS/SETTERS
	
	/**
		* Retrieves the text 'key' that identifies this Argument
		*
		* @since 1.0.0
		*
		* @return string
		*/
	public function getKey()
	{ 
		return $this->key;
	}
	
	/**
		* Retrieves the data 'value' that belongs to this Argument
		*
		* @since 1.0.0
		*
		* @return mixed The data value of this Argument
		*/
	public function getValue()
	{	
		return $this->value;
	}
	
	/**
		* Sets the text 'key' that indentifies this Argument
		* 
		* @since 1.0.0
		*
		* @param string $key
		*/
	public function setKey(string $key)
	{ 
		$this->key = $key;
	}

	/**
		* Sets the data 'value' for this Argument
		* 
		* @since 1.0.0
		*
		* @param mixed $value
		*/
	public function setValue($value)
	{
		$this->value = $value;
	}
	
	/**
		* Returns TRUE when this Arguments 'value' is an array
		*
		* @since 1.0.0
		*
		* @return boolean
		*/
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