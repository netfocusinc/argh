<?php
	
/*
** Command Line Program
** Uses Argh.php to parse arguments supplied to this program
** Demonstrates the capabilities of Argh
*/

//! TODO: Figure out how to "use" this class without including it (? autoloader)

require_once "Argh.php";

use argh\Argh;

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
		]
	]);
	
	echo '\n';
	echo 'DONE';
}
catch(Exception $e)
{
	echo 'Exception: ' . $e->getMessage() . '\n';
}

?>