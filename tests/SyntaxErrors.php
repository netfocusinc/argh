<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\ArgvPreprocessor;
use netfocusinc\argh\BooleanParameter;
use netfocusinc\argh\CommandParameter;
use netfocusinc\argh\IntegerParameter;
use netfocusinc\argh\ListParameter;
use netfocusinc\argh\StringParameter;
use netfocusinc\argh\VariableParameter;

class Trials extends TestCase
{
	
	protected $argh;
	
	//
	// LIFE CYCLE
	//

  protected function setUp()
  {	
	  $this->argh = new Argh(
			[
				CommandParameter::createWithAttributes(
					[
						'name'				=>	'cmd',
						'flag'				=>	'x',
						'required'		=>	FALSE,
						'default'			=>	null,
						'description'	=>	'A command to run.',
						'options'			=>	array('help','joke')				
					]
				),
				ListParameter::createWithAttributes(
					[
						'name'				=>	'colors',
						'flag'				=>	'c',
						'required'		=>	FALSE,
						'default'			=>	null,
						'description'	=>	'List of colors, for fun.'			
					]
				),
				BooleanParameter::createWithAttributes(
					[
						'name'				=>	'debug',
						'flag'				=>	'd',
						'required'		=>	FALSE,
						'default'			=>	FALSE,
						'description'	=>	'Enables debug mode.'					
					]
				),
				StringParameter::createWithAttributes(
					[
						'name'				=>	'file',
						'flag'				=>	'f',
						'required'		=>	FALSE,
						'default'			=>	'sample.out',
						'description'	=>	'File to use (just an example).'				
					]
				),
				IntegerParameter::createWithAttributes(
					[
						'name'				=>	'verbose',
						'flag'				=>	'v',
						'type'				=>	ARGH_TYPE_INT,
						'required'		=>	FALSE,
						'default'			=>	0,
						'description'	=>	'Level of verbosity to output.',
						'options'			=>	array(0, 1, 2, 3)			
					]
				)			
			]
		);
		
  }
	
  //
  // TEST CASES
  //
  
	public function argumentProvider(): array
	{
		
    return [
	  
	  	'Boolean Flag with Illegal Character' =>
	  	[
	  		array('my.php', '-2')
	  	],
	  	
	  	'Double Multiflag with Illegal Character' =>
	  	[
	  		array('my.php', '-d2')	  
	  	],
	  	 
	  	'Parameter Name with Illegal Character' =>
	  	[
	  		array('my.php', '--fa$t')
	  	],
	  	
	  	'Unquoted Value with Illegal Character' =>
	  	[
	  		array('my.php', '-m', 'Unacceptable slash /')
	  	],
	  	
	  	'List with Invalid Delimiter' =>
	  	[
	  		array('my.php', '-m', '[one;two;three]')
	  	]
	  
	  ];
	}
  
  
  /**
	  * @test
	  * @dataProvider argumentProvider()
	  */
	public function argumentsThatCauseSyntaxErrors($argv): void
	{
		
		$this->expectException(ArghException::class);
		
		$this->argh->parse($argv);
		
	}
  
 
}

?>