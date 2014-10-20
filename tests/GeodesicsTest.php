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
    public function testCalc($first, $second, $distance, $bearing, $revers)
    {
        /** @var Geodesics $mock */
        $mock = $this->getMock('GetSky\Geodesics\Geodesics', ['check', 'validation']);
        $method = new ReflectionMethod('GetSky\Geodesics\Geodesics', 'calc');
        $method->setAccessible(true);
        $p1 = new ReflectionProperty('GetSky\Geodesics\Geodesics', 'first');
        $p1->setAccessible(true);
        $p2 = new ReflectionProperty('GetSky\Geodesics\Geodesics', 'second');
        $p2->setAccessible(true);
        $dis = new ReflectionProperty('GetSky\Geodesics\Geodesics', 'distance');
        $dis->setAccessible(true);
        $bea = new ReflectionProperty('GetSky\Geodesics\Geodesics', 'bearing');
        $bea->setAccessible(true);
        $fin = new ReflectionProperty('GetSky\Geodesics\Geodesics', 'finishBearing');
        $fin->setAccessible(true);

        $p1->setValue($mock, $first);
        $p2->setValue($mock, $second);
        $method->invoke($mock);
        $this->assertSame($distance, $dis->getValue($mock));
        $this->assertSame($bearing, $bea->getValue($mock));
        $this->assertSame($revers, $fin->getValue($mock));
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
            [[20, 30], [34, -34], 7235204.7533387318, 167.18321241654618, 166.60384000025635 ],
            [[-45, -50], [-45, -50], 0, null, null ],
            [[68, -90], [180, 67], 17436319.575342514, 111.99999999999999, 8.3294178740204185E-15],
            [[-24, 90], [-34, 0], 10001965.729315577, -170.0, -180.0],
            [[180, 45], [180, -90], 14986910.107297322, 180.0, 180.0],
            [[-180, 34], [45, 76], 7397040.4275098313, -10.778404119271842, -140.24458834308729]
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
