<?php

use PHPUnit\Framework\TestCase;

use netfocusinc\argh\Argh;
use netfocusinc\argh\ArghException;
use netfocusinc\argh\Language;
use netfocusinc\argh\Parameter;
use netfocusinc\argh\Rule;

class LanguageTest extends TestCase
{
  
  //
  // TEST CASES
  //
  
  public function testCreateWithRules()
  {
	  $language = Language::createWithRules();
	  
	  $rules = $language->rules();
	  
	  $this->assertTrue(is_array($rules));
	  
	  $this->assertSame(13, count($rules));
	  
	  $this->assertInstanceOf(Rule::class, $rules[0]);
	}
	
	public function testConstruction()
	{
		$language = new Language();
		
		$rules = $language->rules();
		
		$this->assertTrue(is_array($rules));
		
		$this->assertSame(0, count($rules));
	}
	
	public function testAddRule()
	{
		$language = new Language();
		
		$rules = $language->rules();
		
		$this->assertTrue(is_array($rules));
		
		$this->assertSame(0, count($rules));
		
		$language->addRule(Rule::createWithAttributes(
				[
      		'name'			=>'Hyphenated Flag with Value',
					'example' 	=> '-f value', 
					'syntax'		=> '/^\-(' . Rule::ARGH_SYNTAX_FLAG . ')[\s]+(' . Rule::ARGH_SYNTAX_VALUE . ')$/i',
					'semantics'	=>[Rule::ARGH_SEMANTICS_FLAG, Rule::ARGH_SEMANTICS_VALUE]
				]
			)
     );
     
     $rules = $language->rules();
     
     $this->assertSame(1, count($rules));
	}

}

?>