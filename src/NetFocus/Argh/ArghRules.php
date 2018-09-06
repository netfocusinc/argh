<?php
	
namespace NetFocus\Argh;

// Syntax Contants
define('ARGH_SYN_FLAG', '[a-z]{1}', true);
define('ARGH_SYN_FLAGS', '[a-z]+', true);
define('ARGH_SYN_KEY', '[a-z0-9_]+', true);
define('ARGH_SYN_VALUE', '[a-z0-9_]*', true);
define('ARGH_SYN_LIST', '[a-z0-9_\-, ]+', true);
define('ARGH_SYN_QUOTED', '[a-z0-9_\-\' ]+', true);
define('ARGH_SYN_CMD', '[a-z0-9_]{2,}', true);

// Semantic Contants
define('ARGH_SYM_KEY', 0, true);
define('ARGH_SYM_KEYS', 1, true);
define('ARGH_SYM_VALUE', 2, true);
define('ARGH_SYM_LIST', 3, true);
define('ARGH_SYM_CMD', 4, true);
define('ARGH_SYM_SUB', 5, true);

class ArghRules
{	
	/*
	** PRIVATE MEMBER DATA
	*/
	
	private static $instance = null;
	
	private $rules = null;
	
		
	/*
	** PRIVATE METHODS
	*/
	
	private function __construct()
	{
		 $this->rules = [
			
			[
				'name'			=>	'Hyphenated Flag with List',
				'example'		=>	'-f (one, two, three)',
				'syntax'		=>	'/^\-(' . ARGH_SYN_FLAG . ')[\s]+\[(' . ARGH_SYN_LIST . ')\]$/i',
				'semantics'	=>	[ARGH_SYM_KEY, ARGH_SYM_LIST]
			],
			
			[
				'name'			=>	'Double Hyphenated Flag with List',
				'example'		=>	'--key=(one, two, three)',
				'syntax'		=>	'/^\-\-(' . ARGH_SYN_KEY . ')=\[(' . ARGH_SYN_LIST . ')\]$/i',
				'semantics'	=>	[ARGH_SYM_KEY, ARGH_SYM_LIST]
			],
			
			[
				'name'			=>	'Hyphenated Flag with Quoted Value',
				'example'		=>	'-f \'Hello World\'',
				'syntax'		=>	'/^\-(' . ARGH_SYN_FLAG . ')[\s]+\'(' . ARGH_SYN_QUOTED . ')\'$/i',
				'semantics'	=>	[ARGH_SYM_KEY, ARGH_SYM_VALUE]
			],

			[
				'name'			=>	'Double Hyphenated Key with Quoted Value',
				'example'		=>	'--key=\'quoted value\'',
				'syntax'		=>	'/^\-\-(' . ARGH_SYN_KEY . ')=\'(' . ARGH_SYN_QUOTED . ')\'$/i',
				'semantics'	=>	[ARGH_SYM_KEY, ARGH_SYM_VALUE]
			],
			
			[
				'name'			=>	'Hyphenated Flag with Value',
				'example'		=>	'-f value',
				'syntax'		=>	'/^\-(' . ARGH_SYN_FLAG . ')[\s]+(' . ARGH_SYN_VALUE . ')$/i',
				'semantics'	=>	[ARGH_SYM_KEY, ARGH_SYM_VALUE]
			],
			
			[
				'name'			=>	'Command with Naked Subcommand',
				'example'		=>	'cmd sub',
				'syntax'		=>	'/^(' . ARGH_SYN_CMD . ')[\s]+(' . ARGH_SYN_CMD . ')$/i',
				'semantics'	=>	[ARGH_SYM_CMD, ARGH_SYM_SUB]
			],
	
			[
				'name'			=>	'Double Hyphenated Key with Value',
				'example'		=>	'--key=value',
				'syntax'		=>	'/^\-\-(' . ARGH_SYN_KEY . ')=(' . ARGH_SYN_VALUE . ')$/i',
				'semantics'	=>	[ARGH_SYM_KEY, ARGH_SYM_VALUE]
			],
			
			[
				'name'			=>	'Double Hyphenated Boolean Key',
				'example'		=>	'--key',
				'syntax'		=>	'/^\-\-(' . ARGH_SYN_KEY . ')$/i',
				'semantics'	=>	[ARGH_SYM_KEY]
			],
			
			[
				'name'			=>	'Hyphenated Boolean Flag',
				'example'		=>	'-f',
				'syntax'		=>	'/^\-(' . ARGH_SYN_KEY . ')$/i',
				'semantics'	=>	[ARGH_SYM_KEY]
			],
			
			[
				'name'			=>	'Hyphenated Multi Flag',
				'example'		=>	'-xvf',
				'syntax'		=>	'/^\-(' . ARGH_SYN_FLAGS . ')$/i',
				'semantics'	=>	[ARGH_SYM_KEYS]
			],
			
			[
				'name'			=>	'Command with Delimited Subcommand',
				'example'		=>	'cmd:sub',
				'syntax'		=>	'/^(' . ARGH_SYN_CMD . '):(' . ARGH_SYN_CMD . ')$/i',
				'semantics'	=>	[ARGH_SYM_CMD, ARGH_SYM_SUB]
			],
	
			[
				'name'			=>	'Command',
				'example'		=>	'cmd',
				'syntax'		=>	'/^(' . ARGH_SYN_CMD . ')$/i',
				'semantics'	=>	[ARGH_SYM_CMD]
			],
			
			[
				'name'			=>	'Naked Multi Flag',
				'example'		=>	'xvf',
				'syntax'		=>	'/^(' . ARGH_SYN_FLAGS . ')$/i',
				'semantics'	=>	[ARGH_SYM_KEYS]
			],
			
			[
				'name'			=>	'Naked Variable',
				'example'		=>	'value',
				'syntax'		=>	'/^(' . ARGH_SYN_VALUE . ')$/i',
				'semantics'	=>	[ARGH_SYM_VALUE]
			]
			
		];
		
		return $this;
		
	}
	
	private function __clone() {}
	
	/*
	** PUBLIC METHODS
	*/
	
	public static function instance()
	{
		if(static::$instance === null)
		{
			static::$instance = new static();
		}
		
		return static::$instance;
	}
	
	public static function rules()
	{
		$instance = static::instance();
		return $instance->rules;
	}
	
}
	
?>