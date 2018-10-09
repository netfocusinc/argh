<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\ArgvPreprocessor;
use netfocusinc\argh\BooleanParameter;
use netfocusinc\argh\CommandParameter;
use netfocusinc\argh\IntegerParameter;
use netfocusinc\argh\ListParameter;
use netfocusinc\argh\StringParameter;

class Trials extends TestCase
{
	
	protected $argh;
	
	//
	// LIFE CYCLE
	//

  protected function setUp()
  {	
	  $this->argh = new Argh(
			[
				CommandParameter::createWithAttributes(
					[
						'name'				=>	'cmd',
						'flag'				=>	'x',
						'required'		=>	FALSE,
						'default'			=>	null,
						'description'	=>	'A command to run.',
						'options'			=>	array('help','joke')				
					]
				),
				ListParameter::createWithAttributes(
					[
						'name'				=>	'colors',
						'flag'				=>	'c',
						'required'		=>	FALSE,
						'default'			=>	null,
						'description'	=>	'List of colors, for fun.'			
					]
				),
				BooleanParameter::createWithAttributes(
					[
						'name'				=>	'debug',
						'flag'				=>	'd',
						'required'		=>	FALSE,
						'default'			=>	FALSE,
						'description'	=>	'Enables debug mode.'					
					]
				),
				StringParameter::createWithAttributes(
					[
						'name'				=>	'file',
						'flag'				=>	'f',
						'required'		=>	FALSE,
						'default'			=>	'sample.out',
						'description'	=>	'File to use (just an example).'				
					]
				),
				BooleanParameter::createWithAttributes(
					[
						'name'				=>	'force',
						'flag'				=>	'F',
						'required'		=>	FALSE,
						'default'			=>	FALSE,
						'description'	=>	'Force this operation to complete.'				
					]
				),
				BooleanParameter::createWithAttributes(
					[
						'name'				=>	'summary',
						'flag'				=>	's',
						'required'		=>	FALSE,
						'default'			=>	FALSE,
						'description'	=>	'Show a summary of results.'				
					]
				),
				BooleanParameter::createWithAttributes(
					[
						'name'				=>	'quiet',
						'flag'				=>	'q',
						'required'		=>	FALSE,
						'default'			=>	TRUE,
						'description'	=>	'Suppress output.'				
					]
				),
				IntegerParameter::createWithAttributes(
					[
						'name'				=>	'verbose',
						'flag'				=>	'v',
						'type'				=>	ARGH_TYPE_INT,
						'required'		=>	FALSE,
						'default'			=>	0,
						'description'	=>	'Level of verbosity to output.',
						'options'			=>	array(0, 1, 2, 3)			
					]
				)			
			]
		);
		
  }
	
  //
  // TEST CASES
  //
  
  //
  // SINGULAR ARGUMENTS
  //
  
	/** @test */
	public function singleNakedVariable(): void
	{
		// Naked Variable (Single)
		
		// $ myscript.php somefile.txt
		
		$argv = array('myscript.php', 'somefile.txt');
		
		$this->argh->parse($argv);
		
		$variables = $this->argh->variables();
		
		$this->assertTrue( is_array($variables) );
		
		$this->assertSame( 1, count($variables) );
		
		$this->assertSame( 'somefile.txt', $variables[0] );
		
	}
	
	/** @test */
	public function multipleNakedVariables(): void
	{
		// Naked Variable (Multiple)
		
		// $ myscript.php somefile.txt anotherfile.txt
		
		$argv = array('myscript.php', 'somefile.txt', '/and/anotherfile.txt');
		
		$this->argh->parse($argv);
		
		$variables = $this->argh->variables();
		
		$this->assertTrue( is_array($variables) );
		
		$this->assertSame( 2, count($variables) );
		
		$this->assertSame( 'somefile.txt', $variables[0] );
		
		$this->assertSame( '/and/anotherfile.txt', $variables[1] );
		
	}
	
	/** @test */
	public function commandStringInvalidOption(): void
	{
		// Command Strings (Invalid Option)
		
		// $ myscript.php help
		
		$argv = array('myscript.php', 'wrong');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		// Becomes a naked variable
		//!TODO: Allow new Argh with FLAG to enable/disable naked variables (or other Rules)
		
		$variables = $this->argh->variables();

		$this->assertTrue( is_array($variables) );
		
		$this->assertSame( 1, count($variables) );
		
		$this->assertSame( 'wrong', $variables[0] );		
		
		$this->assertSame(null, $this->argh->cmd);
	
	}
	
	/** @test */
	public function commandString(): void
	{
		// Command Strings (Valid Option)
		
		// $ myscript.php help
		
		$argv = array('myscript.php', 'help');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertSame('help', $this->argh->cmd);
		
	}
	
	/** @test */
	public function hypenatedMultipleBooleanFlagsNoMatch(): void
	{
		// Hypenated Multiple Boolean Flags (No Match)
		
		// $ myscript.php -KFC
		
		$argv = array('myscript.php', '-KFC');
		
		$this->expectException(ArghException::class);
		
		$this->argh->parse($argv);
		
	}
	
	/** @test */
	public function hypenatedMultipleBooleanFlags(): void
	{
		// Hypenated Multiple Boolean Flags (Match)
		
		// $ myscript.php -dFq
		
		$argv = array('myscript.php', '-dFq');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertTrue( $this->argh->d , '--debug isn\'t TRUE');
		
		$this->assertTrue( $this->argh->F , '--force isn\'t TRUE');
		
		$this->assertTrue( $this->argh->q , '--quiet isn\'t TRUE');
	}
	
	/** @test */
	/* NOT CURRENTLY A VALID RULE
	public function hypenatedBooleanFlag(): void
	{
		// Hypenated Boolean Flags (Match)
		
		// $ myscript.php F
		
		$argv = array('myscript.php', 'F');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertTrue( $this->argh->F , '--force isn\'t TRUE');
	}
	*/
	
	/** @test */
	public function hypenatedBooleanParameterName(): void
	{
		// Hypenated Boolean ParameterName (Match)
		
		// $ myscript.php -debug
		
		$argv = array('myscript.php', '-debug');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertTrue( $this->argh->debug , '--debug isn\'t TRUE');
	}
	
	/** @test */
	public function doubleHypenatedBooleanParameterName(): void
	{
		// Double Hypenated Boolean ParameterName (Match)
		
		// $ myscript.php --quiet
		
		$argv = array('myscript.php', '--quiet');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertTrue( $this->argh->quiet , '--quiet isn\'t TRUE');
	}
	
	/** @test */
	public function doubleHypenatedParameterNameWithSingleValue(): void
	{
		// Double Hypenated Parameter Name with Single Value
		
		// $ myscript.php --file=/path/to/somefile.txt
		
		$argv = array('myscript.php', '--file=/path/to/somefile.txt');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertSame( '/path/to/somefile.txt', $this->argh->file);
	}
	
	/** @test */
	public function hypenatedParameterNameWithValue(): void
	{
		// Hypenated Parameter Name with Value
		
		// $ myscript.php -file /path/to/somefile.txt
		
		$argv = array('myscript.php', '-file', '/path/to/somefile.txt');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertSame( '/path/to/somefile.txt', $this->argh->file);
	}
	
	/** @test */
	public function hypenatedFlagWithValue(): void
	{
		// Hypenated Flag with Value
		
		// $ myscript.php -f /path/to/somefile.txt
		
		$argv = array('myscript.php', '-f', '/path/to/somefile.txt');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertSame( '/path/to/somefile.txt', $this->argh->file);
	}
	
	/** @test */
	public function doubleHypenatedParameterNameWithSingleQuotedValue(): void
	{
		// Double Hypenated Parameter Name with Single Quoted Value
		
		// $ myscript.php --msg='Hello World'
		
		$argv = array('myscript.php', '--file=/path with space/file.txt');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertSame( '/path with space/file.txt', $this->argh->file);
		
	}
		
	/** @test */
	public function hypenatedFlagWithSingleQuotedValue(): void
	{
		// Hypenated Flag with Single Quoted Value
		
		// $ myscript.php -m 'Hello World'
		
		$argv = array('myscript.php', '-f', '/path with space/file.txt');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertSame( '/path with space/file.txt', $this->argh->file);
	}
	
	/** @test */
	public function doubleHypenatedParameterNameWithList(): void
	{
		// Double Hypenated Parameter Name With List
		
		// $ myscript.php --colors=[red, green, blue]
		
		$argv = array('myscript.php', '--colors=[red,', 'green,', 'blue]');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$colors = $this->argh->colors;
		
		$this->assertTrue( is_array($colors) );
		
		$this->assertSame(3, count($colors) );
		
		$this->assertSame('green', $colors[1] );
		
	}
	
	/** @test */
	public function doubleHypenatedParameterNameWithListAndQuotes(): void
	{
		// Double Hypenated Parameter Name With List and Quotes
		
		// $ myscript.php --colors=[yellow, blue, 'blue green']
		
		$argv = array('myscript.php', '--colors=[yellow,', 'blue,', 'blue green]');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$colors = $this->argh->colors;
		
		$this->assertTrue( is_array($colors) );
		
		$this->assertSame(3, count($colors) );
		
		$this->assertSame('blue green', $colors[2] );
		
	}
	
	/** @test */
	public function hypenatedFlagWithList(): void
	{
		// Hypenated Flag with List
		
		// $ myscript.php -c [red, green, blue]
		
		$argv = array('myscript.php', '-c', '[red,', 'green,', 'blue]');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$colors = $this->argh->colors;
		
		$this->assertTrue( is_array($colors) );
		
		$this->assertSame(3, count($colors) );
		
		$this->assertSame('green', $colors[1] );
		
	}
	
	/** @test */
	public function hypenatedFlagWithListAndQuotes(): void
	{
		// Hypenated Flag with List and Quotes
		
		// $ myscript.php -c [yellow, blue, 'blue green']
		
		$argv = array('myscript.php', '-c', '[yellow,', 'blue,', 'blue green]');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$colors = $this->argh->colors;
		
		$this->assertTrue( is_array($colors) );
		
		$this->assertSame(3, count($colors) );
		
		$this->assertSame('blue green', $colors[2] );
		
	}
	

	
	
  //
  // COMPOUND ARGUMENTS
  //
  
 	/** @test */
	public function multipleHypenatedBooleanFlags(): void
	{
		// Multiple Hypenated Boolean Flags (Match)
		
		// $ myscript.php -F -q
		
		$argv = array('myscript.php', '-F', '-q');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertTrue( $this->argh->F , '--force isn\'t TRUE');
		
		$this->assertTrue( $this->argh->q , '--quiet isn\'t TRUE');
	}
	
	/** @test */
	public function mulitplehypenatedBooleanParameterName(): void
	{
		// Multiple Hypenated Boolean ParameterName (Match)
		
		// $ myscript.php -debug -force
		
		$argv = array('myscript.php', '-debug', '-force');
		
		$this->argh->parse($argv);
	
		// Test Results
		
		$this->assertTrue( $this->argh->debug , '--debug isn\'t TRUE');
		$this->assertTrue( $this->argh->force , '--force isn\'t TRUE');
	}
  
 
}

?>