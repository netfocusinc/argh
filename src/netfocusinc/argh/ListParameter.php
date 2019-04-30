<?php

/**
	* ListParameter.php
	*/
	
namespace netfocusinc\argh;

use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;


/**
	* A List Parameter.
	*
	* Subtype of Parameter that represents a list of text values.
	*
	* @api
	*
	* @author Benjamin Hough
	*
	* @since 1.0.0
	*/
class ListParameter extends Parameter
{
	
	//
	// STATIC FUNCTIONS
	//
	
	//
	// PUBLIC FUNCTIONS
	//
	
	/**
		* ReturnsParameter::ARGH_TYPE_LIST
		*
		* Identifies this Parameter subtype as a ListParameter by returning constant ARGH_TYPE_LIST
		*
		* @since 1.0.0
		*
		* @return int
		*/
	public function getParameterType(): int
	{
		return Parameter::ARGH_TYPE_LIST;
	}

	/**
		* Sets the array value of this Parameter.
		*
		* Forces all values into an array
		*
		* @since 1.0.0
		*/
	public function setValue($value)
	{		
		if(is_array($value))
		{
			$this->value = $value;
		}
		else
		{
			$this->value = array($value);
		}
	}

	/**
		* Adds an element to the value of this Parameter
		*
		* Forces all values into an array
		*
		* @since 1.0.0
		*
		* @return int
		*/	
	public function addValue($value)
	{
		// Check if this Parameter has a previously set value
		if($this->value === null)
		{
			// Initialize this Parameters value to a new array
			$this->value = array();
		}
		
		// Check if the new value is an array
		if(!is_array($value))
		{
			// Append new single value to this Parameters value array
			$this->value[] = $value;
		}
		else
		{
			// Append every new value to this Parameters value array
			foreach($value as $v) $this->value[] = $v;
		}
	}
	
}
