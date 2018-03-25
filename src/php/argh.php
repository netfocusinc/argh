<?php
	
/*
** Command Line Program
** Uses Argh.php to parse arguments supplied to this program
** Demonstrates the capabilities of Argh
*/

//! TODO: Figure out how to "use" this class without including it (? autoloader)

require_once 'Argh.php';
require_once 'ArghException.php';

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;

/*
// Init memory
*/

try
{
	$argh = new Argh($argv, [
		[
			'name'			=>			'debug',
			'flag'			=>			'd',
			'type'			=>			'boolean',
			'required'	=>			FALSE,
			'default'		=>			FALSE,
			'text'			=>			'Enables debug mode.'
		],
		[
			'name'			=>			'help',
			'flag'			=>			'h',
			'type'			=>			'boolean',
			'required'	=>			FALSE,
			'default'		=>			FALSE,
			'text'			=>			'Displays help text.'
		],
		[
			'name'			=>			'file',
			'flag'			=>			'f',
			'type'			=>			'string',
			'required'	=>			TRUE,
			'default'		=>			NULL,
			'text'			=>			'File to use (just an example).'
		]
	]);
	
	echo "\n\n";
	print_r($argv);
	echo "\n---------------------\n\n";
	
	echo "Command: " . $argh->command . "\n";
	
	//echo "Parameters: \n " . $argh->parametersString() . "\n";
	
	//echo "Arguments: \n " . $argh->argumentsString() . "\n";
	
	echo "\$argh->debug = " . $argh->debug . "\n";
	
	echo "\$argh->file = " . $argh->file . "\n";
	
	echo $argh->usage();
	
	echo "\n";
	echo "DONE";
}
catch(ArghException $e)
{
	echo 'Exception: ' . $e->getMessage() . "\n";
}

?>