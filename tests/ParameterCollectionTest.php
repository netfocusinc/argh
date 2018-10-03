<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\ParameterBoolean;
use netfocusinc\argh\ParameterCommand;
use netfocusinc\argh\ParameterString;
use netfocusinc\argh\ParameterVariable;
use netfocusinc\argh\ParameterCollection;

class ParameterCollectionTest extends TestCase
{
	
	protected $collection;
	
	//
	// LIFE CYCLE
	//

  protected function setUp()
  {	
	  $this->collection = new ParameterCollection();

  }
  
  //
  // TEST CASES
  //
  
  public function testExists(): void
  {
	  $this->collection->addParameter(ParameterBoolean::createWithAttributes(
	  	[
				'name'			=>			'debug',
				'flag'			=>			'd',
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
	  
	  $this->collection->addParameter(ParameterCommand::createWithAttributes(
	  	[
				'name'			=>			'cmd',
				'flag'			=>			'x',
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
	  
	  $this->collection->addParameter(ParameterVariable::createWithAttributes(
	  	[
				'name'			=>			'variable',
				'flag'			=>			'x',
				'required'	=>			FALSE,
				'default'		=>			0,
				'text'			=>			'For Testing Purposes'
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

	  $this->collection->addParameter(ParameterString::createWithAttributes(
	  	[
				'name'			=>			'file',
				'flag'			=>			'f',
				'required'	=>			TRUE,
				'default'		=>			'sample.out',
				'text'			=>			'File to use (just an example).'
			]
		));
		
		$this->assertInstanceOf(ParameterString::class, $this->collection->get('f'));
		
		$this->assertInstanceOf(ParameterString::class, $this->collection->get('file'));
		
	}
	
	public function testGetCommands(): void
	{
		$this->assertTrue(is_array($this->collection->getCommands()));
		
		$this->assertSame(0, count($this->collection->getCommands()));
		
	  $this->collection->addParameter(ParameterCommand::createWithAttributes(
	  	[
				'name'			=>			'cmd',
				'flag'			=>			'x',
				'required'	=>			FALSE,
				'default'		=>			null,
				'text'			=>			'A command to run.',
				'options'		=>			array('help','joke')
			]
		));
	  
	  $this->assertSame(1, count($this->collection->getCommands()));
	  
	  $this->assertInstanceOf(ParameterCommand::class, $this->collection->get('cmd'));
		
	}

}

?>