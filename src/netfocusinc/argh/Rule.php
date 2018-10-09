<?php
	
namespace netfocusinc\argh;

class Rule
{
	//
	// CONSTANTS
	//
	
	// Syntax Contants
	const ARGH_SYNTAX_FLAG			= '[a-z]{1}';
	const ARGH_SYNTAX_FLAGS			= '[a-z]+';
	const ARGH_SYNTAX_NAME			= '[a-z0-9_]+';
	const ARGH_SYNTAX_VALUE			= '[a-z0-9_\.\/]*';
	const ARGH_SYNTAX_LIST			= '[a-z0-9_\-,\' ]+';
	const ARGH_SYNTAX_COMMAND		= '[a-z0-9_]{2,}';
	const ARGH_SYNTAX_QUOTED		= '[a-z0-9_\-\'\\/\. ]+';
	const ARGH_SYNTAX_VARIABLE	= '[a-z0-9_\.\/ ]*';
	
	// Semantic Contants
	const ARGH_SEMANTICS_FLAG			= 1;
	const ARGH_SEMANTICS_FLAGS		= 2;
	const ARGH_SEMANTICS_NAME			= 3;
	const ARGH_SEMANTICS_VALUE		= 4;
	const ARGH_SEMANTICS_LIST			= 5;
	const ARGH_SEMANTICS_COMMAND	= 6;
	const ARGH_SEMANTICS_VARIABLE	= 7;
	
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
	
	public static function createWithAttributes(array $attributes): Rule
	{
		// Defaults
		$name = null;
		$example = null;
		$syntax = null;
		$semantics = null;
		
		if( array_key_exists('name', $attributes) ) $name = $attributes['name'];
		if( array_key_exists('example', $attributes) ) $example = $attributes['example'];
		if( array_key_exists('syntax', $attributes) ) $syntax = $attributes['syntax'];
		if( array_key_exists('semantics', $attributes) ) $semantics = $attributes['semantics'];
		
		return new self($name, $example, $syntax, $semantics);

	}
	
	public static function semanticsToString($semantics)
	{
		switch($semantics)
		{
			case self::ARGH_SEMANTICS_FLAG:			return 'FLAG';
			case self::ARGH_SEMANTICS_FLAGS:		return 'FLAGS';
			case self::ARGH_SEMANTICS_NAME:			return 'NAME';
			case self::ARGH_SEMANTICS_VALUE:		return 'VALUE';
			case self::ARGH_SEMANTICS_LIST:			return 'LIST';
			case self::ARGH_SEMANTICS_COMMAND:	return 'COMMAND';
			case self::ARGH_SEMANTICS_VARIABLE:	return 'VARIABLE';
			default:														return '*invalid*';
		}
	}
	
	//
	// PUBLIC METHODS
	//
	
	public function __construct(string $name, string $example, string $syntax, array $semantics)
	{
		
		// Validate the syntax regular expression
		// Suppress error messages
		if( @preg_match($syntax, '') === FALSE )
		{
			throw new ArghException('Rule \'' . $name . '\' syntax \'' . $syntax .  '\' is not a valid regular expression');
		}
		
		// Confirm count(semantics) matches number of parenthesized subpatterns defined by the syntax regular expression
		if( substr_count($syntax, '(') != count($semantics) )
		{
			throw new ArghException('Rule \'' . $name . '\' syntax defines ' . substr_count($syntax, '(') . ' sub-patterns, but semantics defines ' . count($semantics));
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
	public function name(): string { return $this->name; }

	/**
		* Gets the 'syntax' property of this Rule
		*
		* @return string
	*/
	public function syntax(): string { return $this->syntax; }
	
	/**
		* Gets the 'semantics' property of this Rule
		*
		* @return string
	*/
	public function semantics(): array { return $this->semantics; }
	
	/**
		* Gets the 'sample' of this Rule
		*
		* @return string
	*/
	public function example(): string { return $this->example; }

	/**
		* Does this Rule match a $string
		*
		* Note that matching a Rule does not guarantee the Rule will result
		* in a new Argument. Ultimately, the argument string may not meet all the 
		* requirements of the Rule, e.g. matching defined parameter name|flag|options
		*
		* @param $string string A string to check for match with this Rule
		* @param $tokens array Reference to an array, on match will contain matching elements
		*
		* @return bool
	  */	
	public function match($string, &$tokens=array()): bool
	{
		if( preg_match($this->syntax(), $string, $tokens) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
		
}
	
?>