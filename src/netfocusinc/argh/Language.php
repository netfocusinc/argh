<?php
	
/**
	* Language.php
	*/
	
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

/**
	* Langugage is a set of Rules (with Syntax and Semantics) used to interpret command line arguments.
	*
	* The Language class manages the Rules used to interpret command line arguments during parsing.
	* This class is a container for Rules, and features convenience methods for constructing a language complete with a set of standard rules.
	*
	* @api
	* 
	* @author Benjamin Hough
	*
	* @since 1.0.0
	*/
class Language
{	
	
	//
	// PRIVATE PROPERTIES
	//
	
	/** @var array An array of Rules */
	private $rules; // array of Rule(s)
	
	//
	// STATIC METHODS
	//
	
	/**
		* Creates a Language, complete with a set of Rules used for interpreting command line arguments
		*
		* This is the main function to use when creating a new Langugage.
		*
		* @since 1.0.0
		*
		* @return Language
		*/
	public static function createWithRules()
	{
		// Create a new Language instance
		$language = new self();
		
		//
		// Add a standard set of Rules
		//
		
		// Hyphenated Flag with List
		$language->addRule(new Rule(
				'Hyphenated Flag with List',
				'-f [one, two, three]',
				'/^\-(' . ARGH_SYNTAX_FLAG . ')[\s]+\[(' . ARGH_SYNTAX_LIST . ')\]$/i',
				[ARGH_SEMANTICS_FLAG, ARGH_SEMANTICS_LIST]
			)
		);
		
		// Double Hyphenated Flag with List
		$language->addRule(new Rule(
				'Double Hyphenated Name with List',
				'--key=[one, two, three]',
				'/^\-\-(' . ARGH_SYNTAX_NAME . ')=\[(' . ARGH_SYNTAX_LIST . ')\]$/i',
				[ARGH_SEMANTICS_NAME, ARGH_SEMANTICS_LIST]
			)
		);
		
		// Hyphenated Flag with Quoted Value
		$language->addRule(new Rule(
				'Hyphenated Flag with Quoted Value',
				'-f \'Hello World\'',
				'/^\-(' . ARGH_SYNTAX_FLAG . ')[\s]+\'(' . ARGH_SYNTAX_QUOTED . ')\'$/i',
				[ARGH_SEMANTICS_FLAG, ARGH_SEMANTICS_VALUE]
			)
		);
		
		// Double Hyphenated Key with Quoted Value
		$language->addRule(new Rule(
				'Double Hyphenated Name with Quoted Value',
				'--key=\'quoted value\'',
				'/^\-\-(' . ARGH_SYNTAX_NAME . ')=\'(' . ARGH_SYNTAX_QUOTED . ')\'$/i',
				[ARGH_SEMANTICS_NAME, ARGH_SEMANTICS_VALUE]
			)
		);
		
		// Hyphenated Flag with Value
		$language->addRule(new Rule(
				'Hyphenated Flag with Value',
				'-f value',
				'/^\-(' . ARGH_SYNTAX_FLAG . ')[\s]+(' . ARGH_SYNTAX_VALUE . ')$/i',
				[ARGH_SEMANTICS_FLAG, ARGH_SEMANTICS_VALUE]
			)
		);
		
		/*
		// Command with Naked Subcommand
		$language->addRule(new Rule(
				'Command with Naked Subcommand',
				'cmd sub',
				'/^(' . ARGH_SYNTAX_COMMAND . ')[\s]+(' . ARGH_SYNTAX_COMMAND . ')$/i',
				[ARGH_SEMANTICS_COMMAND, ARGH_SEMANTICS_SUB]
			)
		);
		*/
		
		// Hyphenated Key with Value
		$language->addRule(new Rule(
				'Hyphenated Name with Value',
				'-key value',
				'/^\-(' . ARGH_SYNTAX_NAME . ') (' . ARGH_SYNTAX_VALUE . ')$/i',
				[ARGH_SEMANTICS_NAME, ARGH_SEMANTICS_VALUE]
			)
		);
		
		// Double Hyphenated Key with Value
		$language->addRule(new Rule(
				'Double Hyphenated Name with Value',
				'--key=value',
				'/^\-\-(' . ARGH_SYNTAX_NAME . ')=(' . ARGH_SYNTAX_VALUE . ')$/i',
				[ARGH_SEMANTICS_NAME, ARGH_SEMANTICS_VALUE]
			)
		);
		
		// Double Hyphenated Boolean Key
		$language->addRule(new Rule(
				'Double Hyphenated Boolean Key',
				'--key',
				'/^\-\-(' . ARGH_SYNTAX_NAME . ')$/i',
				[ARGH_SEMANTICS_NAME]
			)
		);
		
		// Hyphenated Boolean Name
		$language->addRule(new Rule(
				'Hyphenated Boolean Key',
				'-key',
				'/^\-(' . ARGH_SYNTAX_NAME . ')$/i',
				[ARGH_SEMANTICS_NAME]
			)
		);
		
		// Hyphenated Boolean Flag
		$language->addRule(new Rule(
				'Hyphenated Boolean Flag',
				'-f',
				'/^\-(' . ARGH_SYNTAX_FLAG . ')$/i',
				[ARGH_SEMANTICS_FLAG]
			)
		);
		
		// Hyphenated Multi Flag
		$language->addRule(new Rule(
				'Hyphenated Multi Flag',
				'-xvf',
				'/^\-(' . ARGH_SYNTAX_FLAGS . ')$/i',
				[ARGH_SEMANTICS_FLAGS]
			)
		);
		
		/*
		// Command with Delimited Subcommand
		$language->addRule(new Rule(
				'Command with Delimited Subcommand',
				'cmd:sub',
				'/^(' . ARGH_SYNTAX_COMMAND . '):(' . ARGH_SYNTAX_COMMAND . ')$/i',
				[ARGH_SEMANTICS_COMMAND, ARGH_SEMANTICS_SUB]
			)
		);
		*/
		
		// Command
		$language->addRule(new Rule(
				'Command',
				'cmd',
				'/^(' . ARGH_SYNTAX_COMMAND . ')$/i',
				[ARGH_SEMANTICS_COMMAND]
			)
		);
		
		// Naked Multi Flag
		$language->addRule(new Rule(
				'Naked Multi Flag',
				'xvf',
				'/^(' . ARGH_SYNTAX_FLAGS . ')$/i',
				[ARGH_SEMANTICS_FLAGS]
			)
		);
		
		// Quoted Naked Variable
		$language->addRule(new Rule(
				'Quoted Naked Variable',
				'value',
				'/^\'(' . ARGH_SYNTAX_QUOTED . ')\'$/i',
				[ARGH_SEMANTICS_VARIABLE]
			)
		);
		
		// Naked Variable
		$language->addRule(new Rule(
				'Naked Variable',
				'value',
				'/^(' . ARGH_SYNTAX_VARIABLE . ')$/i',
				[ARGH_SEMANTICS_VARIABLE]
			)
		);
		
		return $language;
	}
	
	//
	// PUBLIC METHODS
	//
	
	/**
		* Langugage Constructor
		*
		* The preferred way of constructing a new Language, is with the static createWithRules() method.
		* This constructor is useful for creating custom languages, with non-standard rule sets.
		*
		* @since 1.0.0
		*
		* @throws Exception
		*/
	public function __construct()
	{
		try
		{
			// Create an array of Rules
			$this->rules = array();
		}
		catch(Exception $e)
		{
			throw($e);
		}
		
	} // END: __construct()
	
	/**
		* Returns the set of Rules maintained by this Language.
		*
		* @since 1.0.0
		*
		* @return array An array of Rules
		*/
	public function rules()
	{
		return $this->rules;
	}
	
	/**
		* Adds a Rule to the set of rules maintained by this Language.
		*
		* @since 1.0.0
		*
		* @param $rule Rule
		*/
	public function addRule(Rule $rule)
	{
		$this->rules[] = $rule;
	}
	
}
	
?>