<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\Argument;
use netfocusinc\argh\Parameter;

class ArgumentTest extends TestCase
{
  
  //
  // TEST CASES
  //

  
	public function argumentProvider()
  {
    return [
	    
      ['foo', 'bar'],
      ['dim', TRUE],
      ['yin', 17],
      ['colors', array('red','blue','green')]
      
    ];
  }
  
  /**
	  * @dataProvider argumentProvider
	  */
  public function testArguments($key, $value): void
  {
	  
		$argument = new Argument($key, $value);
    
    $this->assertSame($key, $argument->getKey());
    $this->assertSame($value, $argument->getValue());

	}
	
	public function testNotArray(): void
	{
		$argument = new Argument('not-array', 'string');
		
		$this->assertFalse($argument->isArray());
	}
	
	public function testIsArray(): void
	{
		$argument = new Argument('array', array('spaghetti','lasagne'));
		
		$this->assertTrue($argument->isArray());
	}
	
	public function testArray(): void
	{
		$argument = new Argument('array', array('spaghetti','tacos'));
		
		$this->assertTrue($argument->isArray());
		$this->assertSame(2, count($argument->getValue()));
		$this->assertSame('tacos', $argument->getValue()[1]);
	}

}

?>