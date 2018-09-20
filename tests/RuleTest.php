<?php

use PHPUnit\Framework\TestCase;

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;
use NetFocus\Argh\Parameter;
use NetFocus\Argh\Rule;

class RuleTest extends TestCase
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

  
	public function ruleAttributeProvider()
  {
    return [
	    
      [
      	'Hyphenated Flag with Value',
				'-f value', 
				'/^\-(' . Rule::ARGH_SYNTAX_FLAG . ')[\s]+(' . Rule::ARGH_SYNTAX_VALUE . ')$/i',
				[Rule::ARGH_SEMANTICS_FLAG, Rule::ARGH_SEMANTICS_VALUE]
      ],
      
       [
      	'Double Hyphenated Name with Value',
				'--foo=bar', 
				'/^\-\-(' . Rule::ARGH_SYNTAX_NAME . ')=(' . Rule::ARGH_SYNTAX_VALUE . ')$/i',
				[Rule::ARGH_SEMANTICS_NAME, Rule::ARGH_SEMANTICS_VALUE]
      ],
      
      [
      	'Hyphenated Multi Flag',
				'-xvf', 
				'/^\-(' . Rule::ARGH_SYNTAX_FLAGS . ')$/i',
				[Rule::ARGH_SEMANTICS_FLAGS]
      ]
     
    ];
  }
  
  /**
	  * @dataProvider ruleAttributeProvider
	  */
  public function testRuleConstruction($name, $example, $syntax, $semantics): void
  {
	  
		$rule = new Rule($name, $example, $syntax, $semantics);
    
    $this->assertSame($name, $rule->name());
    $this->assertSame($example, $rule->example());
    $this->assertSame($syntax, $rule->syntax());
    $this->assertSame($semantics, $rule->semantics());
	}
	
	public function testInvalidSyntax(): void
	{
		$this->expectException(ArghException::class);
		
		$rule = new Rule('name', 'example', 'not a valid regular express', array());
	}
	
	public function testInvalidSemantics(): void
	{
		$this->expectException(ArghException::class);
		
		$rule = new Rule('name', 'example', '/^(1)(2)(3)$/i', array(Rule::ARGH_SEMANTICS_VALUE));
	}

 
  public function testMatch(): void
  {
	  $rule = new Rule(
	  	'Hyphenated Flag with Value',
	  	'-f value',
	  	'/^\-(' . Rule::ARGH_SYNTAX_FLAG . ')[\s]+(' . Rule::ARGH_SYNTAX_VALUE . ')$/i',
	  	[Rule::ARGH_SEMANTICS_FLAG, Rule::ARGH_SEMANTICS_VALUE]
	  );
	  
	  $this->assertFalse($rule->match('no-match'));
	  
	  $this->assertTrue($rule->match('-m match'));
	}

}

?>