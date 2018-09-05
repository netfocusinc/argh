<?php
	
/*
** Command Line Program
** Uses Argh.php to parse arguments supplied to this program
** Demonstrates the capabilities of Argh
*/

require "vendor/autoload.php";

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;

/*
// Init memory
*/

echo "\n\n---------------------\n";
echo "RAW \$argv:\n";
print_r($argv);
echo "\n---------------------\n\n";

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
			'default'		=>			'sample.out',
			'text'			=>			'File to use (just an example).'
		]
	]);
	
	echo "\n\n";
	echo "\n---------------------\n\n";
	
	echo "Command: " . $argh->command() . "\n";
	
	echo "Parameters: \n" . $argh->parametersString() . "\n";
	
	echo "Arguments: \n" . $argh->argumentsString() . "\n";
	
	echo "Map: \n" . $argh->mapString() . "\n";
		
	echo "\$argh->debug = " . $argh->debug . "\n";
	
	echo "\$argh->help = " . $argh->help . "\n";
	
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