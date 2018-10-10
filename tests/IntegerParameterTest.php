<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;
use netfocusinc\argh\IntegerParameter;

class IntegerParameterTest extends TestCase
{ 
  //
  // TEST CASES
  //
  
  public function testSetValueNonNumeric(): void
  {      
     $parameter = IntegerParameter::createWithAttributes(
     	[
	       	'name'	=>	'test'
      ]
     );
     
     $this->expectException(ArghException::class);
     
     $parameter->setValue('non-numeric-strin');
  }
  
  public function testSetValueNumericString(): void
  {      
     $parameter = IntegerParameter::createWithAttributes(
     	[
	       	'name'	=>	'test'
      ]
     );
          
     $parameter->setValue('22');
     
     $this->assertSame(22, $parameter->getValue());
  }
  
  public function testSetValue(): void
  {      
     $parameter = IntegerParameter::createWithAttributes(
     	[
	       	'name'		=>	'test'
      ]
     );
          
     $parameter->setValue(2);
     
     $this->assertSame(2, $parameter->getValue());
  }
  

}

?>