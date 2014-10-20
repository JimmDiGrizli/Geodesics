<?php
use GetSky\Geodesics\Geodesics;

class GeodesicsTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Geodesics
     */
    private $geodesic;

    /**
     * @dataProvider providerDistancePoint
     */
    public function testDistance($first, $second, $distance)
    {
        $this->geodesic->setFirstPoint($first[0], $first[1]);
        $this->geodesic->setSecondPoint($second[0], $second[1]);
        $this->assertSame($distance, $this->geodesic->distance());
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
            [68, -90],
            [-24, 90],
            [180, 45],
            [-180, 34]
        ];
    }

    public function providerDistancePoint()
    {
        return [
            [[20, 30], [34, -34], 7235204.7533387318],
            [[-45, -50], [-45, -50], 0],
            [[68, -90], [180, 67], 17436319.575342514],
            [[-24, 90], [-34, 0], 10001965.729315577],
            [[180, 45], [180, -90], 14986910.107297322],
            [[-180, 34], [45, 76], 7397040.4275098313]
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
