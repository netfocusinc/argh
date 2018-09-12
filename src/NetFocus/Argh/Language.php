<?php
	
namespace NetFocus\Argh;

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
				'/^\-(' . ARGH_SYN_FLAG . ')[\s]+\[(' . ARGH_SYN_LIST . ')\]$/i',
				[ARGH_SYM_KEY, ARGH_SYM_LIST]
			);
			
			// Double Hyphenated Flag with List
			$this->rules[] = new Rule(
				'Double Hyphenated Flag with List',
				'--key=[one, two, three]',
				'/^\-\-(' . ARGH_SYN_KEY . ')=\[(' . ARGH_SYN_LIST . ')\]$/i',
				[ARGH_SYM_KEY, ARGH_SYM_LIST]
			);
			
			// Hyphenated Flag with Quoted Value
			$this->rules[] = new Rule(
				'Hyphenated Flag with Quoted Value',
				'-f \'Hello World\'',
				'/^\-(' . ARGH_SYN_FLAG . ')[\s]+\'(' . ARGH_SYN_QUOTED . ')\'$/i',
				[ARGH_SYM_KEY, ARGH_SYM_VALUE]
			);
			
			// Double Hyphenated Key with Quoted Value
			$this->rules[] = new Rule(
				'Double Hyphenated Key with Quoted Value',
				'--key=\'quoted value\'',
				'/^\-\-(' . ARGH_SYN_KEY . ')=\'(' . ARGH_SYN_QUOTED . ')\'$/i',
				[ARGH_SYM_KEY, ARGH_SYM_VALUE]
			);
			
			// Hyphenated Flag with Value
			$this->rules[] = new Rule(
				'Hyphenated Flag with Value',
				'-f value',
				'/^\-(' . ARGH_SYN_FLAG . ')[\s]+(' . ARGH_SYN_VALUE . ')$/i',
				[ARGH_SYM_KEY, ARGH_SYM_LIST]
			);
			
			// Command with Naked Subcommand
			$this->rules[] = new Rule(
				'Command with Naked Subcommand',
				'cmd sub',
				'/^(' . ARGH_SYN_CMD . ')[\s]+(' . ARGH_SYN_CMD . ')$/i',
				[ARGH_SYM_CMD, ARGH_SYM_SUB]
			);
			
			// Double Hyphenated Key with Value
			$this->rules[] = new Rule(
				'Double Hyphenated Key with Value',
				'--key=value',
				'/^\-\-(' . ARGH_SYN_KEY . ')=(' . ARGH_SYN_VALUE . ')$/i',
				[ARGH_SYM_KEY, ARGH_SYM_VALUE]
			);
			
			// Double Hyphenated Boolean Key
			$this->rules[] = new Rule(
				'Double Hyphenated Boolean Key',
				'--key',
				'/^\-\-(' . ARGH_SYN_KEY . ')$/i',
				[ARGH_SYM_KEY]
			);
			
			// Hyphenated Boolean Flag
			$this->rules[] = new Rule(
				'Hyphenated Boolean Flag',
				'-f',
				'/^\-(' . ARGH_SYN_KEY . ')$/i',
				[ARGH_SYM_KEY]
			);
			
			// Hyphenated Multi Flag
			$this->rules[] = new Rule(
				'Hyphenated Multi Flag',
				'-xvf',
				'/^\-(' . ARGH_SYN_FLAGS . ')$/i',
				[ARGH_SYM_KEYS]
			);
			
			// Command with Delimited Subcommand
			$this->rules[] = new Rule(
				'Command with Delimited Subcommand',
				'cmd:sub',
				'/^(' . ARGH_SYN_CMD . '):(' . ARGH_SYN_CMD . ')$/i',
				[ARGH_SYM_CMD, ARGH_SYM_SUB]
			);
			
			// Command
			$this->rules[] = new Rule(
				'Command',
				'cmd',
				'/^(' . ARGH_SYN_CMD . ')$/i',
				[ARGH_SYM_CMD]
			);
			
			// Naked Multi Flag
			$this->rules[] = new Rule(
				'Naked Multi Flag',
				'xvf',
				'/^(' . ARGH_SYN_FLAGS . ')$/i',
				[ARGH_SYM_KEYS]
			);
			
			// Naked Variable
			$this->rules[] = new Rule(
				'Naked Variable',
				'value',
				'/^(' . ARGH_SYN_VALUE . ')$/i',
				[ARGH_SYM_VALUE]
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