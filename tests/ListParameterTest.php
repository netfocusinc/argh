<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;
use netfocusinc\argh\ListParameter;

class ListParameterTest extends TestCase
{ 
  //
  // TEST CASES
  //
  
	
	public function testSetValueNonArray(): void
	{
     $parameter = ListParameter::createWithAttributes(
     	[
       	'name'		=>	'test'
      ]
     );
     
     $parameter->setValue('not-an-array');
     
     $this->assertTrue(is_array($parameter->getValue()));
     
     $this->assertSame('not-an-array', $parameter->getValue()[0]);
	}
	
	public function testAddValue(): void
	{
     $parameter = ListParameter::createWithAttributes(
     	[
       	'name'		=>	'test'
      ]
     );
     
     $parameter->addValue('one');
     
     $this->assertTrue(is_array($parameter->getValue()));
     
     $this->assertSame(1, count($parameter->getValue()));
     
     $this->assertSame('one', $parameter->getValue()[0]);
     
     $parameter->addValue('two');
     
     $this->assertSame(2, count($parameter->getValue()));
     
     $this->assertSame('two', $parameter->getValue()[1]);
  
     $parameter->addValue(array('three','four'));
     
     $this->assertSame(4, count($parameter->getValue()));
     
     $this->assertSame('four', $parameter->getValue()[3]);  
     
	}

}

?>