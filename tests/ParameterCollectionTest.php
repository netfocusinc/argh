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
	
	protected $parameters;
	
	//
	// LIFE CYCLE
	//

  protected function setUp()
  {	
	  $this->parameters = new ParameterCollection();

  }
  
  //
  // TEST CASES
  //
  
  public function testExists(): void
  {
	  $this->parameters->addParameter(ParameterBoolean::createWithAttributes(
	  	[
				'name'				=>	'debug',
				'flag'				=>	'd',
				'required'		=>	FALSE,
				'default'			=>	FALSE,
				'description'	=>	'Enables debug mode.'
			]
		));
	  
	  $this->assertFalse($this->parameters->exists('nope'));
	  
	  $this->assertTrue($this->parameters->exists('debug'));
	}
	
  public function testHasCommand(): void
  { 
	  $this->assertFalse($this->parameters->hasCommand());
	  
	  $this->parameters->addParameter(ParameterCommand::createWithAttributes(
	  	[
				'name'			=>			'cmd',
				'flag'			=>			'x',
				'required'	=>			FALSE,
				'default'		=>			null,
				'text'			=>			'A command to run.',
				'options'		=>			array('help','joke')
			]
		));
	  
	  $this->assertTrue($this->parameters->hasCommand());
	}
	
  public function testHasVariable(): void
  { 
	  $this->assertFalse($this->parameters->hasVariable());
	  
	  $this->parameters->addParameter(ParameterVariable::createWithAttributes(
	  	[
				'name'			=>			'variable',
				'flag'			=>			'x',
				'required'	=>			FALSE,
				'default'		=>			0,
				'text'			=>			'For Testing Purposes'
			]
		));
	  
	  $this->assertTrue($this->parameters->hasVariable());
	}
	
	public function testGetUndefined(): void
	{
		$this->expectException(ArghException::class);
		
		$this->parameters->get('nope');
	}
	
	public function testGet(): void
	{

	  $this->parameters->addParameter(ParameterString::createWithAttributes(
	  	[
				'name'			=>			'file',
				'flag'			=>			'f',
				'required'	=>			TRUE,
				'default'		=>			'sample.out',
				'text'			=>			'File to use (just an example).'
			]
		));
		
		$this->assertInstanceOf(ParameterString::class, $this->parameters->get('f'));
		
		$this->assertInstanceOf(ParameterString::class, $this->parameters->get('file'));
		
	}
	
	public function testGetCommands(): void
	{
		$this->assertTrue(is_array($this->parameters->getCommands()));
		
		$this->assertSame(0, count($this->parameters->getCommands()));
		
	  $this->parameters->addParameter(ParameterCommand::createWithAttributes(
	  	[
				'name'			=>			'cmd',
				'flag'			=>			'x',
				'required'	=>			FALSE,
				'default'		=>			null,
				'text'			=>			'A command to run.',
				'options'		=>			array('help','joke')
			]
		));
	  
	  $this->assertSame(1, count($this->parameters->getCommands()));
	  
	  $this->assertInstanceOf(ParameterCommand::class, $this->parameters->get('cmd'));
		
	}

}

?>