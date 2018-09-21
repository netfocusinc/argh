<?php

use PHPUnit\Framework\TestCase;

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;
use NetFocus\Argh\Parameter;
use NetFocus\Argh\ParameterCollection;

class ParameterCollectionTest extends TestCase
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
	  $this->collection = new ParameterCollection();

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
  
  public function testExists(): void
  {
	  $this->collection->addParameter(Parameter::createFromArray(
	  	[
				'name'			=>			'debug',
				'flag'			=>			'd',
				'type'			=>			Parameter::ARGH_TYPE_BOOLEAN,
				'required'	=>			FALSE,
				'default'		=>			FALSE,
				'text'			=>			'Enables debug mode.'
			]
		));
	  
	  $this->assertFalse($this->collection->exists('nope'));
	  
	  $this->assertTrue($this->collection->exists('debug'));
	}
	
  public function testHasCommand(): void
  { 
	  $this->assertFalse($this->collection->hasCommand());
	  
	  $this->collection->addParameter(Parameter::createFromArray(
	  	[
				'name'			=>			'cmd',
				'flag'			=>			'x',
				'type'			=>			Parameter::ARGH_TYPE_COMMAND,
				'required'	=>			FALSE,
				'default'		=>			null,
				'text'			=>			'A command to run.',
				'options'		=>			array('help','joke')
			]
		));
	  
	  $this->assertTrue($this->collection->hasCommand());
	}
	
  public function testHasVariable(): void
  { 
	  $this->assertFalse($this->collection->hasVariable());
	  
	  $this->collection->addParameter(Parameter::createFromArray(
	  	[
				'name'			=>			'variable',
				'flag'			=>			'x',
				'type'			=>			Parameter::ARGH_TYPE_VARIABLE,
				'required'	=>			FALSE,
				'default'		=>			0,
				'text'			=>			'For Testing Purposes',
				'options'		=>			null
			]
		));
	  
	  $this->assertTrue($this->collection->hasVariable());
	}
	
	public function testGetUndefined(): void
	{
		$this->expectException(ArghException::class);
		
		$this->collection->get('nope');
	}
	
	public function testGet(): void
	{

	  $this->collection->addParameter(Parameter::createFromArray(
	  	[
				'name'			=>			'file',
				'flag'			=>			'f',
				'type'			=>			Parameter::ARGH_TYPE_STRING,
				'required'	=>			TRUE,
				'default'		=>			'sample.out',
				'text'			=>			'File to use (just an example).'
			]
		));
		
		$this->assertInstanceOf(Parameter::class, $this->collection->get('f'));
		
	}
	
	public function testGetCommands(): void
	{
		$this->assertTrue(is_array($this->collection->getCommands()));
		
		$this->assertSame(0, count($this->collection->getCommands()));
		
	  $this->collection->addParameter(Parameter::createFromArray(
	  	[
				'name'			=>			'cmd',
				'flag'			=>			'x',
				'type'			=>			Parameter::ARGH_TYPE_COMMAND,
				'required'	=>			FALSE,
				'default'		=>			null,
				'text'			=>			'A command to run.',
				'options'		=>			array('help','joke')
			]
		));
	  
	  $this->assertSame(1, count($this->collection->getCommands()));
		
	}

}

?>