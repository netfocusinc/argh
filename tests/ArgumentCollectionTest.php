<?php

use PHPUnit\Framework\TestCase;

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;
use NetFocus\Argh\Argument;
use NetFocus\Argh\ArgumentCollection;

class ArgumentCollectionTest extends TestCase
{
	
	protected $collection;
	
	//
	// LIFE CYCLE
	//
	
	public static function setUpBeforeClass()
	{
		//fwrite(STDOUT, __METHOD__ . "\n");
	}

  protected function setUp()
  {	
	  $this->collection = new ArgumentCollection();

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
  
  public function testAddArgument()
  {
	  $this->collection->addArgument(new Argument('key', 'value'));
	  
	  $this->expectException(ArghException::class);
	  
	  $this->collection->addArgument(new Argument('key', 'value2'));
	}
  
  public function testExists(): void
  { 
	  $this->assertFalse($this->collection->exists('key'));
	  
	  $this->collection->addArgument(new Argument('key', 'value'));
	  
	  $this->assertTrue($this->collection->exists('key'));
	}
	
	public function testGetUndefined(): void
	{
		$this->expectException(ArghException::class);
		
		$this->collection->get('key');
	}
	
	public function testGet(): void
	{
		$this->collection->addArgument(new Argument('key', 'value'));
		
		$this->assertInstanceOf(Argument::class, $this->collection->get('key'));
	}
	
	public function testAll(): void
	{
		$this->assertSame(0, count($this->collection->all()));
		
		$this->collection->addArgument(new Argument('key1', 'value1'));
		$this->collection->addArgument(new Argument('key2', 'value2'));
		
		$this->assertSame(2, count($this->collection->all()));
	}

}

?>