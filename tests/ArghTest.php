<?php

use PHPUnit\Framework\TestCase;

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;
use NetFocus\Argh\ArgvPreprocessor;

class ArghTest extends TestCase
{
		
	//
	// LIFE CYCLE
	//
	
	public static function setUpBeforeClass()
	{
		//fwrite(STDOUT, __METHOD__ . "\n");
	}

  protected function setUp()
  {	
  }
  
  protected function assertPreConditions()
  {
    //fwrite(STDOUT, __METHOD__ . "\n");
  }
  
  protected function tearDown()
  {
    //fwrite(STDOUT, __METHOD__ . "\n");
  }

  public static function tearDownAfterClass()
  {
    //fwrite(STDOUT, __METHOD__ . "\n");
  }

	/*
  protected function onNotSuccessfulTest(Exception $e)
  {
    fwrite(STDOUT, __METHOD__ . "\n");
    throw $e;
  }
  */
  
  //
  // TEST CASES
  //
  
	public function testParseException(): void
	{
		$this->expectException(ArghException::class);
		
		$parameters = [
		
			[
				'name'=>''
			]
		
		];
		
		Argh::parse( array('my.php'), $parameters );
	}
  
 
}

?>