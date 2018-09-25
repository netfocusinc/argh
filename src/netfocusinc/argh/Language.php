<?php
	
namespace netfocusinc\argh;

//
// CONVENIENCE DEFINITIONS
//

// Syntax Contants
define('ARGH_SYNTAX_FLAG', Rule::ARGH_SYNTAX_FLAG, true);
define('ARGH_SYNTAX_FLAGS', Rule::ARGH_SYNTAX_FLAGS, true);
define('ARGH_SYNTAX_NAME', Rule::ARGH_SYNTAX_NAME, true);
define('ARGH_SYNTAX_VALUE', Rule::ARGH_SYNTAX_VALUE, true);
define('ARGH_SYNTAX_LIST', Rule::ARGH_SYNTAX_LIST, true);
define('ARGH_SYNTAX_COMMAND', Rule::ARGH_SYNTAX_COMMAND, true);
define('ARGH_SYNTAX_QUOTED', Rule::ARGH_SYNTAX_QUOTED, true);
define('ARGH_SYNTAX_VARIABLE', Rule::ARGH_SYNTAX_VARIABLE, true);

// Semantic Contants
define('ARGH_SEMANTICS_FLAG', Rule::ARGH_SEMANTICS_FLAG, true);
define('ARGH_SEMANTICS_FLAGS', Rule::ARGH_SEMANTICS_FLAGS, true);
define('ARGH_SEMANTICS_NAME', Rule::ARGH_SEMANTICS_NAME, true);
define('ARGH_SEMANTICS_VALUE', Rule::ARGH_SEMANTICS_VALUE, true);
define('ARGH_SEMANTICS_LIST', Rule::ARGH_SEMANTICS_LIST, true);
define('ARGH_SEMANTICS_COMMAND', Rule::ARGH_SEMANTICS_COMMAND, true);
define('ARGH_SEMANTICS_VARIABLE', Rule::ARGH_SEMANTICS_VARIABLE, true);

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
			
			/*
			// Command with Naked Subcommand
			$this->rules[] = new Rule(
				'Command with Naked Subcommand',
				'cmd sub',
				'/^(' . ARGH_SYNTAX_COMMAND . ')[\s]+(' . ARGH_SYNTAX_COMMAND . ')$/i',
				[ARGH_SEMANTICS_COMMAND, ARGH_SEMANTICS_SUB]
			);
			*/
			
			// Hyphenated Key with Value
			$this->rules[] = new Rule(
				'Hyphenated Name with Value',
				'-key value',
				'/^\-(' . ARGH_SYNTAX_NAME . ') (' . ARGH_SYNTAX_VALUE . ')$/i',
				[ARGH_SEMANTICS_NAME, ARGH_SEMANTICS_VALUE]
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
			
			// Hyphenated Boolean Name
			$this->rules[] = new Rule(
				'Hyphenated Boolean Key',
				'-key',
				'/^\-(' . ARGH_SYNTAX_NAME . ')$/i',
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
			
			/*
			// Command with Delimited Subcommand
			$this->rules[] = new Rule(
				'Command with Delimited Subcommand',
				'cmd:sub',
				'/^(' . ARGH_SYNTAX_COMMAND . '):(' . ARGH_SYNTAX_COMMAND . ')$/i',
				[ARGH_SEMANTICS_COMMAND, ARGH_SEMANTICS_SUB]
			);
			*/
			
			// Command
			$this->rules[] = new Rule(
				'Command',
				'cmd',
				'/^(' . ARGH_SYNTAX_COMMAND . ')$/i',
				[ARGH_SEMANTICS_COMMAND]
			);
			
			/*
			// Naked Multi Flag
			$this->rules[] = new Rule(
				'Naked Multi Flag',
				'xvf',
				'/^(' . ARGH_SYNTAX_FLAGS . ')$/i',
				[ARGH_SEMANTICS_FLAGS]
			);
			*/
			
			// Naked Variable
			$this->rules[] = new Rule(
				'Naked Variable',
				'value',
				'/^(' . ARGH_SYNTAX_VARIABLE . ')$/i',
				[ARGH_SEMANTICS_VARIABLE]
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
	
	public function rules()
	{
		return $this->rules;
	}
	
	
}
	
?>