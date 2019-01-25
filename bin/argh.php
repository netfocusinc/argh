<?php
	
/*
** Command Line Program
** Uses Argh.php to parse arguments supplied to this program
** Demonstrates the capabilities of Argh
*/

require 'vendor/autoload.php';

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\BooleanParameter;
use netfocusinc\argh\CommandParameter;
use netfocusinc\argh\IntegerParameter;
use netfocusinc\argh\ListParameter;
use netfocusinc\argh\StringParameter;
use netfocusinc\argh\VariableParameter;

try
{
	echo "Before new Argh()\n";
	$argh = new Argh([
			BooleanParameter::createWithAttributes(
				[
					'name'				=>	'debug',
					'flag'				=>	'd',
					'required'		=>	FALSE,
					'default'			=>	FALSE,
					'description'	=>	'Enables debug mode.'
				]
			),
			CommandParameter::createWithAttributes(
				[
					'name'				=>	'cmd',
					'flag'				=>	'x',
					'required'		=>	FALSE,
					'default'			=>	null,
					'description'	=>	'A command to run.',
					'options'			=>	array('help','version','show')
				]
			),
			IntegerParameter::createWithAttributes(
				[
					'name'				=>	'verbose',
					'flag'				=>	'V',
					'required'		=>	FALSE,
					'default'			=>	0,
					'description'	=>	'Level of verbosity to output.',
					'options'			=>	array(0, 1, 2, 3)
				]
			)
		]
	);
	
	$argh->parseArguments($argv);
	
	switch($argh->cmd)
	{
		case 'help':
		
			echo $argh->usage() . "\n";
			
			break;
			
		case 'show':
		
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
				if(ARGH_TYPE_VARIABLE != $p->getParameterType())
				{
					echo '$argh->' . $p->getName() . ' = ';
					
					if( !is_array($argh->get($p->getName())) )
					{
						echo $argh->get($p->getName()) . "\n";
					}
					else
					{
						$buff = '[';
						for($i=0; $i<count($argh->get($p->getName())); $i++)
						{
							$e = $argh->get($p->getName())[$i];
							$buff .= $e . ', ';
						}
						$buff = substr($buff, 0, -2); // remove trailing ', '
						$buff .= ']';
						
						echo $buff;
						echo "\n";
					}
				} // END: if(ARGH_TYPE_VARIABLE != $p->type())
		
			} // END: foreach($argh->parameters()->all() as $p)
			
			break;
		
		case 'version':
		
			echo "Argh! 0.2.0 by Benjamin Hough, Net Focus Inc.\n";
			
			break;
		
		default:
		
			echo $argh->usage() . "\n";
	} // switch($argh->cmd)
	
} // try
catch(ArghException $e)
{
	echo 'Exception: ' . $e->getMessage() . "\n";
}

?>