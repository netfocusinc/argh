<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\ArgvPreprocessor;
use netfocusinc\argh\ArgumentParser;
use netfocusinc\argh\ParameterBoolean;
use netfocusinc\argh\ParameterCollection;
use netfocusinc\argh\ParameterString;
use netfocusinc\argh\Language;

class ArgumentParserTest extends TestCase
{
	
	protected $argumentParser;
	
	protected $args;
		
	//
	// LIFE CYCLE
	//

  protected function setUp()
  {
	  $language = Language::createWithRules();
	  
	  $collection = new ParameterCollection();
	  
	  $collection->addParameter(ParameterBoolean::createWithAttributes(
	  	[
				'name'			=>			'debug',
				'flag'			=>			'd',
				'required'	=>			FALSE,
				'default'		=>			FALSE,
				'text'			=>			'Enables debug mode.'
			]
		));
		
		$this->argumentParser = new ArgumentParser($language, $collection);

  }
	
  //
  // TEST CASES
  //
  
  public function testNoArgs(): void
  {
	  $args = array();
	  
	  $arguments = $this->argumentParser->parse($args);
	  
	  $this->assertSame(0, count($arguments));
	}
	
  public function testSyntaxError(): void
  {
	  $argv = array('myscript.php', '~nomatch');
	  
	  $args = ArgvPreprocessor::process($argv);
	  
	  $this->expectException(ArghException::class);
	  
	  $arguments = $this->argumentParser->parse($args);
	}
	
	public function testParse(): void
	{
		$argv = array('myscript.php', '--debug=1');
		
		$args = ArgvPreprocessor::process($argv);
		
	  $arguments = $this->argumentParser->parse($args);
	  
	  $this->assertSame(1, count($arguments));
	  
	  $this->assertSame('debug', $arguments[0]->getKey());
	  
	  $this->assertSame('1', $arguments[0]->getValue());
		
	}
  
	
  
 
}

?>