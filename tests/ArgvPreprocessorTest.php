<?php

use PHPUnit\Framework\TestCase;

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;
use NetFocus\Argh\ArgvPreprocessor;

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
	  	
	  	'Double Hyphenated Name with Value' =>
	  	[
	  		array('my.php', '--file=out.txt'),
	  		array('--file=out.txt'),
	  	],
	  	
	  	'List with Spaces' =>
	  	[
	  		array('my.php', '--file=[one,', 'two,', 'three]'),
	  		array('--file=[one,two,three]'),
	  	],
	  	
	  	'Quoted Value with Sapces' =>
	  	[
	  		array('my.php', '-m', 'Hello World'),
	  		array('-m', "'Hello World'"),
	  	],
	  	
	  	'Quoted Value with Spaces' =>
	  	[
	  		array('my.php', '-m', "Hello O'Malley" ),
	  		array('-m', '"Hello O\'Malley"'),
	  	],
	  	
	  	'List with Quoted Items' =>
	  	[
	  		array('my.php', '--colors=[blue,', 'green,', 'blue green]'),
	  		array("--colors=[blue,green,'blue green']"),
	  	],
	  	
	  	'List with Quoted Items' =>
	  	[
	  		array('my.php', '--msgs=[Hi,', 'Hey,', 'Hello World!]'),
	  		array('--msgs=[Hi,Hey,"Hello World!"]'),
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