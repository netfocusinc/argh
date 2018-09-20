<?php

use PHPUnit\Framework\TestCase;

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;
use NetFocus\Argh\Language;
use NetFocus\Argh\Parameter;
use NetFocus\Argh\Rule;

class LanguageTest extends TestCase
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

  public function testSingleton(): void
  {
	  $this->expectException(Error::class);
		$language = new Language();
	}
	
	public function testSingletonInstance(): void
	{
		$language = Language::instance();
		
		$this->assertInstanceOf(Language::class, $language);
	}
	
	public function testRules()
	{
		$language = Language::instance();
		
		$this->assertTrue(is_array($language->rules()));
		$this->assertTrue(count($language->rules()) > 0);
		$this->assertInstanceOf(Rule::class, $language->rules()[0]);
	}

}

?>