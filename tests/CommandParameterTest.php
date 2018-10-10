<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\Parameter;
use netfocusinc\argh\CommandParameter;

class CommandParameterTest extends TestCase
{ 
  //
  // TEST CASES
  //
  
  public function testConstructWithoutOptions(): void
  {      
	  $this->expectException(ArghException::class);
	  
    $parameter = CommandParameter::createWithAttributes(
     	[
	       	'name'	=>	'cmd'
      ]
    );
     
  }
  
	public function testSetValueArray(): void
	{
     $parameter = CommandParameter::createWithAttributes(
     	[
       	'name'		=>	'cmd',
	      'options'	=>	array('one','two','three')
      ]
     );
     
     $this->expectException(ArghException::class);
     
     $parameter->setValue(array('red','green'));	
	}
	
	public function testSetValueInvalidOption(): void
	{
     $parameter = CommandParameter::createWithAttributes(
     	[
       	'name'		=>	'cmd',
	      'options'	=>	array('one','two','three')
      ]
     );
     
     $this->expectException(ArghException::class);
     
     $parameter->setValue('four');	
	}
	
	public function testSetValue(): void
	{
     $parameter = CommandParameter::createWithAttributes(
     	[
       	'name'		=>	'cmd',
	      'options'	=>	array('one','two','three')
      ]
     );
          
     $parameter->setValue('two');
     
     $this->assertSame('two', $parameter->getValue());	
	}

}

?>