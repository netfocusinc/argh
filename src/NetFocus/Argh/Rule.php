<?php
	
namespace NetFocus\Argh;

// Syntax Contants
define('ARGH_SYN_FLAG', '[a-z]{1}', true);
define('ARGH_SYN_FLAGS', '[a-z]+', true);
define('ARGH_SYN_KEY', '[a-z0-9_]+', true);
define('ARGH_SYN_VALUE', '[a-z0-9_]*', true);
define('ARGH_SYN_LIST', '[a-z0-9_\-,\' ]+', true);
define('ARGH_SYN_QUOTED', '[a-z0-9_\-\' ]+', true);
define('ARGH_SYN_CMD', '[a-z0-9_]{2,}', true);

// Semantic Contants
define('ARGH_SYM_KEY', 0, true);
define('ARGH_SYM_KEYS', 1, true);
define('ARGH_SYM_VALUE', 2, true);
define('ARGH_SYM_LIST', 3, true);
define('ARGH_SYM_CMD', 4, true);
define('ARGH_SYM_SUB', 5, true);

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