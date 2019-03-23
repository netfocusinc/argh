<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\ArgvPreprocessor;
use netfocusinc\argh\BooleanParameter;
use netfocusinc\argh\StringParameter;

class ArghTest extends TestCase
{
	
  //
  // TEST CASES
  //
  
	public function testParseWithParameters(): void
	{		// Simulate $argv array
		$argv = array('myscript.php', '--debug');
		
		// Create parameters array
		$parameters = [
			BooleanParameter::createWithAttributes(
				[
					'name'	=>	'debug'
				]
			)
		];		
		
		// Parse with Parameters
		$argh = Argh::parse( $argv, $parameters );
		
		$this->assertTrue($argh->get('debug'));
		// Simulate $argv array
		$argv = array('myscript.php', '--debug');
		
		// Create parameters array
		$parameters = [
			BooleanParameter::createWithAttributes(
				[
					'name'	=>	'debug'
				]
			)
		];		
		
		// Parse with Parameters
		$argh = Argh::parse( $argv, $parameters );
		
		$this->assertTrue($argh->get('debug'));
	}
	
	public function testParseStringWithParameters(): void
	{
		// Simulate $argv array
		$args = 'myscript.php --debug';
		
		// Create parameters array
		$parameters = [
			BooleanParameter::createWithAttributes(
				[
					'name'	=>	'debug'
				]
			)
		];		
		
		// Parse with Parameters
		$argh = Argh::parseString( $args, $parameters );
		
		$this->assertTrue($argh->get('debug'));
	}
	
	public function testMagicGet()
	{
		// Simulate $argv array
		$args = 'myscript.php -m Hello';
		
		// Create parameters array
		$parameters = [
			StringParameter::createWithAttributes(
				[
					'name'	=>	'message',
					'flag'	=>	'm'
				]
			)
		];		
		
		// Parse with Parameters
		$argh = Argh::parseString( $args, $parameters );
		
		$this->assertSame('Hello', $argh->message);
		$this->assertSame('Hello', $argh->m);	
	}
	
	public function testMagicIsSet()
	{
		// Simulate $argv array
		$args = 'myscript.php -m Hello';
		
		// Create parameters array
		$parameters = [
			StringParameter::createWithAttributes(
				[
					'name'	=>	'message',
					'flag'	=>	'm'
				]
			)
		];		
		
		// Parse with Parameters
		$argh = Argh::parseString( $args, $parameters );
		
		$this->assertTrue(isset($argh->message));
		$this->assertFalse(isset($argh->nope));	
	}
	
	public function testArgv()
	{
		// Simulate $argv array
		$args = 'myscript.php --debug';
		
		// Create parameters array
		$parameters = [
			BooleanParameter::createWithAttributes(
				[
					'name'	=>	'debug'
				]
			)
		];		
		
		// Parse with Parameters
		$argh = Argh::parseString( $args, $parameters );
		
		$this->assertSame('myscript.php', $argh->argv(0));
		
		$this->assertSame('--debug', $argh->argv(1));
		
		$this->assertTrue( is_array($argh->argv()) );
		
		$this->expectException(ArghException::class);
		
		$argh->argv(9);
				
	}
	
	public function testGetUndefined()
	{
		// Simulate $argv array
		$args = 'myscript.php -m Bar';
		
		// Create parameters array
		$parameters = [
			StringParameter::createWithAttributes(
				[
					'name'		=>	'message',
					'flag'		=>	'm',
					'default'	=>	'Foo'
				]
			)
		];		
		
		// Parse with Parameters
		$argh = Argh::parseString( $args, $parameters );
		
		$this->expectException(ArghException::class);
		
		$argh->get('nope');
	}
	
	public function testGetDefault()
	{
		// Simulate $argv array
		$args = 'myscript.php';
		
		// Create parameters array
		$parameters = [
			StringParameter::createWithAttributes(
				[
					'name'		=>	'message',
					'flag'		=>	'm',
					'default'	=>	'Foo'
				]
			)
		];		
		
		// Parse with Parameters
		$argh = Argh::parseString( $args, $parameters );
		
		// Default Value
		$this->assertSame('Foo', $argh->get('message'));
		$this->assertSame('Foo', $argh->get('m'));
	}
	
	public function testGet()
	{
		// Simulate $argv array
		$args = 'myscript.php -m Bar';
		
		// Create parameters array
		$parameters = [
			StringParameter::createWithAttributes(
				[
					'name'		=>	'message',
					'flag'		=>	'm',
					'default'	=>	'Foo'
				]
			)
		];		
		
		// Parse with Parameters
		$argh = Argh::parseString( $args, $parameters );
		
		// Default Value
		$this->assertSame('Bar', $argh->get('message'));
		$this->assertSame('Bar', $argh->get('m'));
	}
  
 
}

?>