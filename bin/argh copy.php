<?php
	
/*
** Command Line Program
** Uses Argh.php to parse arguments supplied to this program
** Demonstrates the capabilities of Argh
*/

require "vendor/autoload.php";

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;

/*
// Init memory
*/

echo "\n---------------------\n";
echo "RAW \$argv:\n";
print_r($argv);
echo "---------------------\n\n";

try
{
	$argh = Argh::parse($argv, [
		[
			'name'			=>			'debug',
			'flag'			=>			'd',
			'type'			=>			ARGH_TYPE_BOOLEAN,
			'required'	=>			FALSE,
			'default'		=>			FALSE,
			'text'			=>			'Enables debug mode.'
		],
		[
			'name'			=>			'cmd',
			'flag'			=>			'x',
			'type'			=>			ARGH_TYPE_COMMAND,
			'required'	=>			FALSE,
			'default'		=>			null,
			'text'			=>			'A command to run.',
			'options'		=>			array('help','joke')
		],
		[
			'name'			=>			'file',
			'flag'			=>			'f',
			'type'			=>			ARGH_TYPE_STRING,
			'required'	=>			TRUE,
			'default'		=>			'sample.out',
			'text'			=>			'File to use (just an example).'
		],
		[
			'name'			=>			'colors',
			'flag'			=>			'c',
			'type'			=>			ARGH_TYPE_LIST,
			'required'	=>			FALSE,
			'default'		=>			null,
			'text'			=>			'List of colors, for fun.'
		],
		[
			'name'			=>			'verbose',
			'flag'			=>			'v',
			'type'			=>			ARGH_TYPE_INT,
			'required'	=>			FALSE,
			'default'		=>			0,
			'text'			=>			'Level of verbosity to output.',
			'options'		=>			array(0, 1, 2, 3)
		]
	]);
	
	echo "\n\n";
	
	if("help" == $argh->cmd )
	{
		echo $argh->usage() . "\n";
	}
	else if("joke" == $argh->cmd )
	{
		echo "Why did the chicken cross the road?\n";
	}
	else
	{
	
		echo "Command: " . $argh->command() . "\n";
		
		echo "Parameters: \n" . $argh->parameters()->toString() . "\n";
		
		if( $argh->parameters()->hasVariable() )
		{
			echo "Variables: \n";
			print_r($argh->variables());
			echo "\n";
		}
		
		// Show values for each parameter (exclude variables)
		foreach($argh->parameters()->all() as $p)
		{
			if(ARGH_TYPE_VARIABLE != $p->type())
			{
				echo '$argh->' . $p->name() . ' = ';
				
				if( !is_array($argh->get($p->name())) )
				{
					echo $argh->get($p->name()) . "\n";
				}
				else
				{
					echo '[ ';
					foreach($argh->get($p->name()) as $e)
					{
						echo $e . ' ';
					}
					echo ' ]';
					echo "\n";
				}
			} // END: if(ARGH_TYPE_VARIABLE != $p->type())
		}
	
	} // END: foreach($argh->parameters()->all() as $p)
	
}
catch(ArghException $e)
{
	echo 'Exception: ' . $e->getMessage() . "\n";
}

?>