<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;
use netfocusinc\argh\StringParameter;

class StringParameterTest extends TestCase
{ 
  //
  // TEST CASES
  //
  	
	public function testSetValueToArray(): void
	{
     $parameter = StringParameter::createWithAttributes(
     	[
       	'name'	=>	'test'
      ]
     );
     
     $this->expectException(ArghException::class);
     
     $parameter->setValue(array('one','two'));	
	}
	
	public function testSetValue(): void
	{
     $parameter = StringParameter::createWithAttributes(
     	[
       	'name'		=>	'test'
      ]
     );
     
     $parameter->setValue('Hello World');
     
     $this->assertSame('Hello World', $parameter->getValue());		
	}

}

?>