<?php
	
/**
	* Rule.php
	*/
	
namespace netfocusinc\argh;

/**
	* Representation of a Rule used to interpret command line arguments
	*
	* Rules are a combination of syntax and semantics used to interpret command line arguments.
	* When a command line string matches the syntax of a rule, and its character content matches the sematics of the rule
	* this command line string can be used to create an Argument.
	*
	* @author Benjamin Hough
	*
	* @since 1.0.0
	*/
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
	const ARGH_SYNTAX_VARIABLE	= '[a-z0-9_\.\/]*';
	
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
	
	/** @var string The 'name' of this Rule */
	private $name = null;
	
	/** @var string An 'example' string that matches the syntax of this Rule */
	private $example = null;
	
	/** @var string A regular expression defining the acceptable syntax for this Rule */
	private $syntax = null;
	
	/** @var int One of Rule's contants defining the semantical meaning for this Rule */
	private $semantics = null;
	
	//
	// STATIC FUNCTIONS
	//
	
	/**
		* Creates a new Rule with the specified $attributes
		*
		* Convenience method for creating new Rules with the specified $attributes
		*
		* @since 1.0.0
		*
		* @returns Rule
		*/
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
	
	/**
		* Returns a (human friendly) string representation of a semantic int
		*
		* @since 1.0.0
		*
		* @param int $semantics
		*
		* @returns string
		*/
	public static function semanticsToString(int $semantics)
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
	
	/**
		* Rule contructor.
		*
		* Constructs a new Rule.
		*
		* @since 1.0.0
		*
		* @param string $name
		* @param string $example
		* @param string $syntax
		* @param array $semantics
		*
		* @throws ArghException
		*/
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
		* @since 1.0.0
		*
		* @return string
	*/
	public function name(): string { return $this->name; }

	/**
		* Gets the 'syntax' property of this Rule
		*
		* @since 1.0.0
		*
		* @return string
	*/
	public function syntax(): string { return $this->syntax; }
	
	/**
		* Gets the 'semantics' property of this Rule
		*
		* @since 1.0.0
		*
		* @return array
	*/
	public function semantics(): array { return $this->semantics; }
	
	/**
		* Gets the 'sample' of this Rule
		*
		* @since 1.0.0
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