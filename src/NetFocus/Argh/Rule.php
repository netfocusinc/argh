<?php
	
namespace NetFocus\Argh;

class Rule
{	
	//
	// PUBLIC PROPERTIES
	//
	
	private $name = null;
	private $example = null;
	private $syntax = null;
	private $semantics = null;
	
	//
	// STATIC FUNCTIONS
	//
	
	public static function semanticsToString($semantics)
	{
		switch($semantics)
		{
			case ARGH_SEMANTICS_FLAG:		return 'FLAG';
			case ARGH_SEMANTICS_FLAGS:	return 'FLAGS';
			case ARGH_SEMANTICS_NAME:		return 'NAME';
			case ARGH_SEMANTICS_VALUE:	return 'VALUE';
			case ARGH_SEMANTICS_LIST:		return 'LIST';
			case ARGH_SEMANTICS_CMD:		return 'CMD';
			case ARGH_SEMANTICS_SUB:		return 'SUB';
			default:										return '*invalid*';
		}
	}
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct($name, $example, $syntax, $semantics)
	{
		
			// Make sure the rule contains all required elements
		if( empty($name) )
		{
			throw new ArghException('Rule is missing required name');
		}
		
		if( empty($example) )
		{
			throw new ArghException('Rule \'' . $name . '\' is missing required example');
		}
		
		if( empty($syntax) )
		{
			throw new ArghException('Rule \'' . $name . '\' is missing required syntax');
		}
		else
		{
			// Validate the syntax regular expression
			// Suppress error messages
			if( @preg_match($syntax, '') === FALSE )
			{
				throw new ArghException('Rule \'' . $name . '\' syntax \'' . $syntax .  '\' is not a valid regular expression');
			}
		}
		
		if( empty($semantics) )
		{
			throw new ArghException('Rule \'' . $name . '\' is missing required semantics');
		}
		else
		{
			if( !is_array($semantics) )
			{
				throw new ArghException('Expecting array for rule \'' . $name . '\' semantics, ' . gettype($semantics) . ' given');
			}
			else
			{
				// Confirm count(semantics) matches number of parenthesized subpatterns defined by the syntax regular expression
				if( substr_count($syntax, '(') != count($semantics) )
				{
					throw new ArghException('Rule \'' . $name . '\' syntax defines ' . substr_count($syntax, '(') . ' sub-patterns, but semantics defines ' . count($semantics));
				}
				
			}
		}
		
		// Set properties on this instance
		$this->name = $name;
		$this->syntax = $syntax;
		$this->semantics = $semantics;
		$this->example = $example;
	}

	/**
		* Gets the 'name' property of this Rule
		*
		* @return string
	*/
	public function name() { return $this->name; }

	/**
		* Gets the 'syntax' property of this Rule
		*
		* @return string
	*/
	public function syntax() { return $this->syntax; }
	
	/**
		* Gets the 'semantics' property of this Rule
		*
		* @return string
	*/
	public function semantics() { return $this->semantics; }
	
	/**
		* Gets the 'sample' of this Rule
		*
		* @return string
	*/
	public function example() { return $this->example; }
		
}
	
?>