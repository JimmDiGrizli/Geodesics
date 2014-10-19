<?php
use GetSky\Geodesics\Geodesics;

class GeodesicsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Geodesics
     */
    private $geodesic;

    public function testDistance()
    {
        $distance = $this->geodesic->distance([20, 39], [40, 76]);
        $this->assertSame('100', $distance);
    }

    /**
     * @dataProvider providerPoint
     */
    public function testSetFirstPoint($x, $y)
    {
        /** @var Geodesics $mock */
        $mock = $this->getMock('GetSky\Geodesics\Geodesics', ['validation']);
        $mock->setFirstPoint($x, $y);
        $this->assertSame([$x, $y], $mock->getFirstPoint());
    }

    /**
     * @dataProvider providerPoint
     */
    public function testSetSecondPoint($x, $y)
    {
        /** @var Geodesics $mock */
        $mock = $this->getMock('GetSky\Geodesics\Geodesics', ['validation']);
        $mock->setSecondPoint($x, $y);
        $this->assertSame([$x, $y], $mock->getSecondPoint());
    }

    /**
     * @expectedException Exception
     * @dataProvider providerPointException
     */
    public function testValidationException($x, $y)
    {
        $method = new ReflectionMethod('GetSky\Geodesics\Geodesics', 'validation');
        $method->setAccessible(true);
        $method->invoke($this->geodesic, $x, $y);
    }

    /**
     * @dataProvider providerPoint
     */
    public function testValidation($x, $y)
    {
        $method = new ReflectionMethod('GetSky\Geodesics\Geodesics', 'validation');
        $method->setAccessible(true);
        $method->invoke($this->geodesic, $x, $y);
    }

    public function providerPoint()
    {
        return [
            [20, 30],
            [-45, -50],
            [68, -24],
            [-24, 24],
            [180, 45],
            [-180, 34]
        ];
    }

    public function providerPointException()
    {
        return [
            [200, 20],
            [181, -50],
            [-181, -24],
            [-200, 24],
            [23, 100],
            [-45, 91],
            [134, -91],
            [-27, -100]
        ];
    }

    protected function setUp()
    {
        $this->geodesic = new Geodesics();
    }
}
