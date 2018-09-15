<?php

use PHPUnit\Framework\TestCase;

use NetFocus\Argh\Argh;
use NetFocus\Argh\ArghException;
use NetFocus\Argh\Parameter;

class ParameterTest extends TestCase
{
    protected $parameter;

    protected function setUp()
    {
    }

    public function testCreateFromArrayTypeError()
    {
       $this->expectException(TypeError::class);
        
       $parameter = Parameter::createFromArray('string');
    }
    
    public function testCreateFromArrayMissingName()
    {
       $this->expectException(ArghException::class);
        
       $parameter = Parameter::createFromArray(
       	[
	       	"name"=>null
	      ]
       );
    }

}

?>