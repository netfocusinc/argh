<?php

use PHPUnit\Framework\TestCase;

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;
use NetFocus\Argh\Parameter;

class ParameterTest extends TestCase
{
	
	//
	// LIFE CYCLE
	//
	
	public static function setUpBeforeClass()
	{
		//fwrite(STDOUT, __METHOD__ . "\n");
	}

  protected function setUp()
  {
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

  public function testCreateFromArrayTypeError(): void
  {
     $this->expectException(TypeError::class);
      
     $parameter = Parameter::createFromArray('string');
  }
  
  public function testCreateFromArrayMissingName(): void
  {
     $this->expectException(ArghException::class);
      
     $parameter = Parameter::createFromArray(
     	[
       	'name'=>null
      ]
     );
  }
  
  public function testCreateFromArrayInvalidType(): void
  {
     $this->expectException(ArghException::class);
      
     $parameter = Parameter::createFromArray(
     	[
       	'name'=>'notempty',
       	'type'=>-1
      ]
     );
  }
  
  public function testBooleanDefaultFalse(): void
  {
      
     $parameter = Parameter::createFromArray(
		 	[
      	'name'		=>	'test',
      	'type'		=>	Parameter::ARGH_TYPE_BOOLEAN			]
     );
     
     $this->assertFalse($parameter->default());
     
  }
  
  public function testBooleanDefaultTrue(): void
  {
      
     $parameter = Parameter::createFromArray(
		 	[
      	'name'		=>	'test',
      	'type'		=>	Parameter::ARGH_TYPE_BOOLEAN,
				'default'	=>	'notempty'	
			]
     );
     
     $this->assertTrue($parameter->default());
  }
  
	public function testCommandMissingOptions(): void
	{
		
		$this->expectException(ArghException::class);
		
   $parameter = Parameter::createFromArray(
	 	[
    	'name'		=>	'test',
    	'type'		=>	Parameter::ARGH_TYPE_COMMAND			]
   );
     		
	}
  
	public function parameterAttributeProvider()
  {
    return [
	    
      'Outfile'  => [
      	'outfile',
      	'o',
      	Parameter::ARGH_TYPE_STRING,
      	FALSE,
      	'/default/outfile.txt',
      	'Path to a file where output will be written.'
      ],
      
      'Verbose'  => [
      	'verbose',
      	'v',
      	Parameter::ARGH_TYPE_BOOLEAN,
      	FALSE,
      	TRUE,
      	'Include extra output.'
      ],
      
      'Debug Mode'  => [
      	'debug',
      	'd',
      	Parameter::ARGH_TYPE_BOOLEAN,
      	FALSE,
      	TRUE,
      	'Run program in DEBUG mode.'
      ],
      
      'Limit'  => [
      	'limit',
      	'm',
      	Parameter::ARGH_TYPE_INT,
      	TRUE,
      	FALSE,
      	'Maximum number of operations to complete.'
      ]
      
    ];
  }
  
  /**
	  * @dataProvider parameterAttributeProvider
	  */
  public function testParameterAttributes($name, $flag, $type, $required, $default, $text): void
  {
	  
		$parameter = Parameter::createFromArray(
			[
				'name'			=>	$name,
				'flag'			=>	$flag,
				'type'			=>	$type,
				'required'	=>	$required,
				'default'		=>	$default,
				'text'			=>	$text	
			]
    );
    
    $this->assertSame($name, $parameter->name());
    $this->assertSame($flag, $parameter->flag());
    $this->assertSame($required, $parameter->required());
    $this->assertSame($default, $parameter->default());
    $this->assertSame($text, $parameter->text());
    	  
	}
	
	public function testNoOptions(): void
	{
		$parameter = Parameter::createFromArray(
			[
				'name'			=>	'no-options',
				'type'			=>	Parameter::ARGH_TYPE_STRING
			]
    );
    
    $this->assertFalse($parameter->hasOptions());
	}
	
	public function testHasOptions(): void
	{
		$parameter = Parameter::createFromArray(
			[
				'name'			=>	'has-options',
				'type'			=>	Parameter::ARGH_TYPE_STRING,
				'options'		=>	array('one', 'two', 'three')
			]
    );
    
    $this->assertTrue($parameter->hasOptions());
	}
	
	public function testOptions(): void
	{
		$parameter = Parameter::createFromArray(
			[
				'name'			=>	'test-options',
				'type'			=>	Parameter::ARGH_TYPE_STRING,
				'options'		=>	array('one', 'two', 'three')
			]
    );
    
    $this->assertSame(3, count($parameter->options()));
	}
	
	public function testIsOption(): void
	{
		$parameter = Parameter::createFromArray(
			[
				'name'			=>	'has-options',
				'type'			=>	Parameter::ARGH_TYPE_STRING,
				'options'		=>	array('one', 'two', 'three')
			]
    );
    
    $this->assertTrue($parameter->isOption('two'));
	}
	
	public function testIsNotOption(): void
	{
		$parameter = Parameter::createFromArray(
			[
				'name'			=>	'has-options',
				'type'			=>	Parameter::ARGH_TYPE_STRING,
				'options'		=>	array('one', 'two', 'three')
			]
    );
    
    $this->assertFalse($parameter->isOption('four'));
	}

}

?>