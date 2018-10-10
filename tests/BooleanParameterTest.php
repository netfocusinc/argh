<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;
use netfocusinc\argh\BooleanParameter;

class BooleanParameterTest extends TestCase
{ 
  //
  // TEST CASES
  //
  
  public function testdefaultFalse(): void
  {      
     $parameter = BooleanParameter::createWithAttributes(
     	[
	       	'name'	=>	'test'
      ]
     );
     
     $this->assertFalse($parameter->getDefault());
  }
  
	public function testDefaultTrue(): void
	{
     $parameter = BooleanParameter::createWithAttributes(
     	[
       	'name'		=>	'test',
	      'default'	=>	TRUE
      ]
     );
     
     $this->assertTrue($parameter->getDefault());		
	}
	
	public function testSetValueNull(): void
	{
     $parameter = BooleanParameter::createWithAttributes(
     	[
       	'name'		=>	'test',
	      'default'	=>	FALSE
      ]
     );
     
     $parameter->setValue(null);
     
     $this->assertTrue($parameter->getValue());		
	}
	
	public function testSetValueFalse(): void
	{
     $parameter = BooleanParameter::createWithAttributes(
     	[
       	'name'		=>	'test',
	      'default'	=>	TRUE
      ]
     );
     
     $parameter->setValue(FALSE);
     
     $this->assertFalse($parameter->getValue());		
	}
	
	public function testSetValueFalseString(): void
	{
     $parameter = BooleanParameter::createWithAttributes(
     	[
       	'name'		=>	'test',
	      'default'	=>	TRUE
      ]
     );
     
     $parameter->setValue('Off');
     
     $this->assertFalse($parameter->getValue());		
	}
	
	public function testSetValueTrue(): void
	{
     $parameter = BooleanParameter::createWithAttributes(
     	[
       	'name'		=>	'test',
	      'default'	=>	FALSE
      ]
     );
     
     $parameter->setValue('Ok');
     
     $this->assertTrue($parameter->getValue());		
	}

}

?>