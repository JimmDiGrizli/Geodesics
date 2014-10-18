<?php

use GetSky\Geodesics\BaseValidation;

class BaseValidationTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var BaseValidation
     */
    protected $validation;

    public function testValidationInterface() {
        $this->assertInstanceOf('GetSky\Geodesics\Validation', $this->validation);
    }

    public function testValid() {
        $this->assertSame(true, $this->validation->valid());
    }
    protected function setUp() {
        $this->validation = new BaseValidation();
    }
} 