/*
** Command Line Program
** Uses Argh.php to parse arguments supplied to this program
** Demonstrates the capabilities of Argh
*/

require_once ('argp.php');

/*
// Init memory
*/

try
{
	$argh = new Argh($argv, [
		[
			'name': 'debug',
			'flag': 'd',
			'type': 'boolean',
			'required': FALSE,
			'default': FALSE
		]
	]);
}
catch(Exception $e)
{
	echo 'Exception: ' . $e->getMessage() . '\n';
}