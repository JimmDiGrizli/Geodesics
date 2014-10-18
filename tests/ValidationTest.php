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

    /**
     * @dataProvider providerPoint
     */
    public function testValid($x, $y) {
        $this->assertSame(true, $this->validation->valid([$x, $y]));
    }

    /**
     * @expectedException Exception
     * @dataProvider providerPointException
     */
    public function testValidException($x, $y) {
        $this->validation->valid([$x, $y]);
    }

    public function providerPoint() {
        return [
            [20, 30],
            [-45, -50],
            [68, -24],
            [-24, 24],
            [180, 45],
            [-180, 34]
        ];
    }

    public function providerPointException() {
        return [
            [200, 20],
            [181, -50],
            [-181, -24],
            [-200, 24],
            [23, 100],
            [-45, 91 ],
            [134, -91],
            [-27, -100]
        ];
    }

    protected function setUp() {
        $this->validation = new BaseValidation();
    }
} 