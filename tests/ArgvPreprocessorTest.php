<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\ArgvPreprocessor;

class ArgvPreprocessorTest extends TestCase
{
		
	//
	// LIFE CYCLE
	//

  protected function setUp()
  {	
  }
  
  //
  // TEST CASES
  //
  
  public function testEmptyArgv(): void
  { 
	  $this->expectException(ArghException::class);
	  
	  ArgvPreprocessor::process(array());
	}
	
	public function processArgvProvider(): array
	{
		
    return [
	  
	  	'Hyphenated Flag with Value' =>
	  	[
	  		array('my.php', '-f', 'value'),
	  		array('-f', 'value'),
	  	],
	  	
	  	'Double Hyphenated Parameter Name with Value' =>
	  	[
	  		array('my.php', '--file=out.txt'),
	  		array('--file=out.txt'),
	  	],
	  	
	  	'Double Hyphenated Parameter Name with Quoted Value' =>
	  	[
	  		array('my.php', '--msg=Hello World'),
	  		array("--msg='Hello World'"),
	  	],
	  	
	  	'List with Spaces' =>
	  	[
	  		array('my.php', '--file=[one,', 'two,', 'three]'),
	  		array('--file=[one,two,three]'),
	  	],
	  	
	  	'Quoted Value with Spaces' =>
	  	[
	  		array('my.php', '-m', 'Hello World'),
	  		array('-m', "'Hello World'"),
	  	],
	  	
	  	'Quoted Value with Spaces' =>
	  	[
	  		array('my.php', '-m', "Hello O'Malley" ),
	  		array('-m', "'Hello O'Malley'"),
	  	],
	  	
	  	'List with Quoted Items' =>
	  	[
	  		array('my.php', '--colors=[blue,', 'green,', 'blue green]'),
	  		array("--colors=[blue,green,blue green]"),
	  	],
	  	
	  	'List with Quoted Items' =>
	  	[
	  		array('my.php', '--msgs=[Hi,', 'Hey,', 'Hello World!]'),
	  		array('--msgs=[Hi,Hey,Hello World!]'),
	  	]
	  
	  ];
	}
	
  /**
	  * @dataProvider processArgvProvider()
	  */
	public function testProcess($argv, $expected): void
	{
		$this->assertSame($expected, ArgvPreprocessor::process($argv)); 
	}
  
 
}

?>