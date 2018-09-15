<?php
	
namespace NetFocus\Argh;

//
// CONVENIENCE DEFINITIONS
//

// Syntax Contants
define('ARGH_SYNTAX_FLAG', Rule::ARGH_SYNTAX_FLAG, true);
define('ARGH_SYNTAX_FLAGS', Rule::ARGH_SYNTAX_FLAGS, true);
define('ARGH_SYNTAX_NAME', Rule::ARGH_SYNTAX_NAME, true);
define('ARGH_SYNTAX_VALUE', Rule::ARGH_SYNTAX_VALUE, true);
define('ARGH_SYNTAX_LIST', Rule::ARGH_SYNTAX_LIST, true);
define('ARGH_SYNTAX_CMD', Rule::ARGH_SYNTAX_CMD, true);
define('ARGH_SYNTAX_QUOTED', Rule::ARGH_SYNTAX_QUOTED, true);

// Semantic Contants
define('ARGH_SEMANTICS_FLAG', Rule::ARGH_SEMANTICS_FLAG, true);
define('ARGH_SEMANTICS_FLAGS', Rule::ARGH_SEMANTICS_FLAGS, true);
define('ARGH_SEMANTICS_NAME', Rule::ARGH_SEMANTICS_NAME, true);
define('ARGH_SEMANTICS_VALUE', Rule::ARGH_SEMANTICS_VALUE, true);
define('ARGH_SEMANTICS_LIST', Rule::ARGH_SEMANTICS_LIST, true);
define('ARGH_SEMANTICS_CMD', Rule::ARGH_SEMANTICS_CMD, true);
define('ARGH_SEMANTICS_SUB', Rule::ARGH_SEMANTICS_SUB, true);

class Language
{	
	
	//
	// PRIVATE PROPERTIES
	//
	
	private static $instance = null;
	
	private $rules = null; // array of Rule(s)
	
		
	//
	// PRIVATE METHODS
	//
	
	private function __construct()
	{
		try
		{
			// Create an array of Rules
			$this->rules = array();
			
			//
			// Define a standard set of Rules that make up the Language
			//
			
			// Hyphenated Flag with List
			$this->rules[] = new Rule(
				'Hyphenated Flag with List',
				'-f [one, two, three]',
				'/^\-(' . ARGH_SYNTAX_FLAG . ')[\s]+\[(' . ARGH_SYNTAX_LIST . ')\]$/i',
				[ARGH_SEMANTICS_FLAG, ARGH_SEMANTICS_LIST]
			);
			
			// Double Hyphenated Flag with List
			$this->rules[] = new Rule(
				'Double Hyphenated Name with List',
				'--key=[one, two, three]',
				'/^\-\-(' . ARGH_SYNTAX_NAME . ')=\[(' . ARGH_SYNTAX_LIST . ')\]$/i',
				[ARGH_SEMANTICS_NAME, ARGH_SEMANTICS_LIST]
			);
			
			// Hyphenated Flag with Quoted Value
			$this->rules[] = new Rule(
				'Hyphenated Flag with Quoted Value',
				'-f \'Hello World\'',
				'/^\-(' . ARGH_SYNTAX_FLAG . ')[\s]+\'(' . ARGH_SYNTAX_QUOTED . ')\'$/i',
				[ARGH_SEMANTICS_FLAG, ARGH_SEMANTICS_VALUE]
			);
			
			// Double Hyphenated Key with Quoted Value
			$this->rules[] = new Rule(
				'Double Hyphenated Name with Quoted Value',
				'--key=\'quoted value\'',
				'/^\-\-(' . ARGH_SYNTAX_NAME . ')=\'(' . ARGH_SYNTAX_QUOTED . ')\'$/i',
				[ARGH_SEMANTICS_NAME, ARGH_SEMANTICS_VALUE]
			);
			
			// Hyphenated Flag with Value
			$this->rules[] = new Rule(
				'Hyphenated Flag with Value',
				'-f value',
				'/^\-(' . ARGH_SYNTAX_FLAG . ')[\s]+(' . ARGH_SYNTAX_VALUE . ')$/i',
				[ARGH_SEMANTICS_FLAG, ARGH_SEMANTICS_VALUE]
			);
			
			// Command with Naked Subcommand
			$this->rules[] = new Rule(
				'Command with Naked Subcommand',
				'cmd sub',
				'/^(' . ARGH_SYNTAX_CMD . ')[\s]+(' . ARGH_SYNTAX_CMD . ')$/i',
				[ARGH_SEMANTICS_CMD, ARGH_SEMANTICS_SUB]
			);
			
			// Double Hyphenated Key with Value
			$this->rules[] = new Rule(
				'Double Hyphenated Name with Value',
				'--key=value',
				'/^\-\-(' . ARGH_SYNTAX_NAME . ')=(' . ARGH_SYNTAX_VALUE . ')$/i',
				[ARGH_SEMANTICS_NAME, ARGH_SEMANTICS_VALUE]
			);
			
			// Double Hyphenated Boolean Key
			$this->rules[] = new Rule(
				'Double Hyphenated Boolean Key',
				'--key',
				'/^\-\-(' . ARGH_SYNTAX_NAME . ')$/i',
				[ARGH_SEMANTICS_NAME]
			);
			
			// Hyphenated Boolean Flag
			$this->rules[] = new Rule(
				'Hyphenated Boolean Flag',
				'-f',
				'/^\-(' . ARGH_SYNTAX_FLAG . ')$/i',
				[ARGH_SEMANTICS_FLAG]
			);
			
			// Hyphenated Multi Flag
			$this->rules[] = new Rule(
				'Hyphenated Multi Flag',
				'-xvf',
				'/^\-(' . ARGH_SYNTAX_FLAGS . ')$/i',
				[ARGH_SEMANTICS_FLAGS]
			);
			
			// Command with Delimited Subcommand
			$this->rules[] = new Rule(
				'Command with Delimited Subcommand',
				'cmd:sub',
				'/^(' . ARGH_SYNTAX_CMD . '):(' . ARGH_SYNTAX_CMD . ')$/i',
				[ARGH_SEMANTICS_CMD, ARGH_SEMANTICS_SUB]
			);
			
			// Command
			$this->rules[] = new Rule(
				'Command',
				'cmd',
				'/^(' . ARGH_SYNTAX_CMD . ')$/i',
				[ARGH_SEMANTICS_CMD]
			);
			
			// Naked Multi Flag
			$this->rules[] = new Rule(
				'Naked Multi Flag',
				'xvf',
				'/^(' . ARGH_SYNTAX_FLAGS . ')$/i',
				[ARGH_SEMANTICS_FLAGS]
			);
			
			// Naked Variable
			$this->rules[] = new Rule(
				'Naked Variable',
				'value',
				'/^(' . ARGH_SYNTAX_VALUE . ')$/i',
				[ARGH_SEMANTICS_VALUE]
			);
			
			// Return singleton instance
			return $this;
		}
		catch(Exception $e)
		{
			throw($e);
		}
		
	} // END: __construct()
	
	private function __clone() {}
	
	//
	// STATIC METHODS
	//
	
	public static function instance()
	{
		if(static::$instance === null)
		{
			static::$instance = new static();
		}
		
		return static::$instance;
	}
	
	//
	// PUBLIC METHODS
	//
	
	public function addRule(Rule $rule)
	{
		$this->rules[] = $rule;
	}
	
	public function rules()
	{
		return $this->rules;
	}

	
}
	
?>