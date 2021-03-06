<?php
	
/*
** Command Line Program
** Uses Argh.php to parse arguments supplied to this program
** Demonstrates the capabilities of Argh
*/

require __DIR__ . '/../vendor/autoload.php';

use netfocusinc\argh\About;
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
	$argh = new Argh([
			VariableParameter::create(),
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
					'options'			=>	array('about','help','show','version')
				]
			),
			IntegerParameter::createWithAttributes(
				[
					'name'				=>	'verbose',
					'flag'				=>	'v',
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
		case 'about':
		
			echo About::$name . ' ' . About::$version . ' by ' . About::$author . ' - ' . About::$url . PHP_EOL;
			
			break;
			
		case 'help':
		
			echo $argh->usage() . PHP_EOL;
			
			break;
			
		case 'show':
		
			echo "Parameters: \n" . $argh->parameters()->toString() . PHP_EOL;
			
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
						echo $argh->get($p->getName()) . PHP_EOL;
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
						echo PHP_EOL;
					}
				} // END: if(ARGH_TYPE_VARIABLE != $p->type())
		
			} // END: foreach($argh->parameters()->all() as $p)
			
			break;
		
		case 'version':
		
			echo About::$version . PHP_EOL;
			
			break;
		
		default:
		
			echo '? Try: php bin/argh.php help' . PHP_EOL;
	} // switch($argh->cmd)
	
} // try
catch(ArghException $e)
{
	echo 'Exception: ' . $e->getMessage() . PHP_EOL;
}

?>
