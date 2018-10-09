<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;
use netfocusinc\argh\StringParameter;

class ParameterTest extends TestCase
{ 
  //
  // TEST CASES
  //
  
  public function testCreateWithAttributes(): void
  {      
     $parameter = StringParameter::createWithAttributes(
     	[
       	'name'				=>	'test',
       	'flag'				=>	't',
       	'required'		=>	FALSE,
       	'default'			=>	FALSE,
       	'description'	=>	'Test Description'
      ]
     );
     
     $this->assertInstanceOf(StringParameter::class, $parameter);
     $this->assertInstanceOf(Parameter::class, $parameter);
     $this->assertSame('test', $parameter->getName());
     $this->assertSame('t', $parameter->getFlag());
     $this->assertFalse($parameter->isRequired());
     $this->assertFalse($parameter->getDefault());
     $this->assertSame('Test Description', $parameter->getDescription());
     $this->assertFalse($parameter->hasOptions());
     
  }
  
  public function testCreateWithAttributesEmptyName()
  {
	  $this->expectException(ArghException::class);
	  
		$parameter = StringParameter::createWithAttributes(
			[
		 		'name'				=>	'',
		 		'flag'				=>	't',
		 		'required'		=>	FALSE,
		 		'default'			=>	FALSE,
		 		'description'	=>	'Test Description'
		 	]
		);	
		  
	}
	
  public function testConstruction()
  {
	  
		$parameter = new StringParameter('foo', 't', TRUE, 'bar', 'Foo Bar');
		
		$this->assertInstanceOf(StringParameter::class, $parameter);
		
		$this->assertInstanceOf(Parameter::class, $parameter);   
	}
	
	public function testNoOptions(): void
	{
		$parameter = StringParameter::createWithAttributes(
			[
				'name'	=>	'no-options'
			]
    );
    
    $this->assertFalse($parameter->hasOptions());
	}
	
	public function testHasOptions(): void
	{
		$parameter = StringParameter::createWithAttributes(
			[
				'name'			=>	'has-options',
				'options'		=>	array('one', 'two', 'three')
			]
    );
    
    $this->assertTrue($parameter->hasOptions());
	}
	
	public function testOptions(): void
	{
		$parameter = StringParameter::createWithAttributes(
			[
				'name'			=>	'test-options',
				'options'		=>	array('one', 'two', 'three')
			]
    );
    
    $this->assertSame(3, count($parameter->getOptions()));
    
    $this->assertTrue($parameter->isOption('two'));
    
    $this->assertFalse($parameter->isOption('purple'));
    
	}

}

?>