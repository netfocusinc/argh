<?php

use PHPUnit\Framework\TestCase;

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;
use NetFocus\Argh\Argument;
use NetFocus\Argh\Parameter;

class ArgumentTest extends TestCase
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

  
	public function argumentProvider()
  {
    return [
	    
      ['foo', 'bar', Parameter::ARGH_TYPE_STRING],
      ['dim', TRUE, Parameter::ARGH_TYPE_BOOLEAN],
      ['yin', 17, Parameter::ARGH_TYPE_INT],
      ['colors', array('red','blue','green'), Parameter::ARGH_TYPE_LIST]
      
    ];
  }
  
  /**
	  * @dataProvider argumentProvider
	  */
  public function testArguments($key, $value, $type): void
  {
	  
		$argument = new Argument($key, $value, $type);
    
    $this->assertSame($key, $argument->key());
    $this->assertSame($value, $argument->value());
    $this->assertSame($type, $argument->type());

	}
	
	public function testNotArray(): void
	{
		$argument = new Argument('not-array', 'string');
		
		$this->assertFalse($argument->isArray());
	}
	
	public function testIsArray(): void
	{
		$argument = new Argument('array', array('spaghetti','lasagne'), Parameter::ARGH_TYPE_LIST);
		
		$this->assertTrue($argument->isArray());
	}
	
	public function testArray(): void
	{
		$argument = new Argument('array', array('spaghetti','tacos'), Parameter::ARGH_TYPE_LIST);
		
		$this->assertTrue($argument->isArray());
		$this->assertSame(2, count($argument->value()));
		$this->assertSame('tacos', $argument->value()[1]);
	}

}

?>