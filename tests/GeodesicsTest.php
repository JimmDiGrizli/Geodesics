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
        $mock = $this->getMock('GetSky\Geodesics\Geodesics', ['check']);
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
        $fin = new ReflectionProperty(
            'GetSky\Geodesics\Geodesics',
            'finishBearing'
        );
        $fin->setAccessible(true);

        $mockFirst = $this->getMockBuilder('GetSky\Geodesics\Point')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $lon = new ReflectionProperty('GetSky\Geodesics\Point', 'longitude');
        $lon->setAccessible(true);
        $lon->setValue($mockFirst, $first[0]);
        $lat = new ReflectionProperty('GetSky\Geodesics\Point', 'latitude');
        $lat->setAccessible(true);
        $lat->setValue($mockFirst, $first[1]);
        $p1->setValue($mock, $mockFirst);

        $mockSecond = $this->getMockBuilder('GetSky\Geodesics\Point')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $lon = new ReflectionProperty('GetSky\Geodesics\Point', 'longitude');
        $lon->setAccessible(true);
        $lon->setValue($mockSecond, $second[0]);
        $lat = new ReflectionProperty('GetSky\Geodesics\Point', 'latitude');
        $lat->setAccessible(true);
        $lat->setValue($mockSecond, $second[1]);
        $p2->setValue($mock, $mockSecond);

        $method->invoke($mock);
        $this->assertSame($distance, $dis->getValue($mock));
        $this->assertSame($bearing, $bea->getValue($mock));
        $this->assertSame($revers, $fin->getValue($mock));
    }

    public function testCheck()
    {
        /** @var Geodesics $mock */
        $mock = $this->getMock('GetSky\Geodesics\Geodesics', ['calc']);
        $mock->expects($this->once())->method('calc');

        $method = new ReflectionMethod('GetSky\Geodesics\Geodesics', 'check');
        $method->setAccessible(true);
        $method->invoke($mock);

        $dis = new ReflectionProperty('GetSky\Geodesics\Geodesics', 'distance');
        $dis->setAccessible(true);
        $dis->setValue($mock, 0);

        $method->invoke($mock);
    }

    /**
     * @dataProvider providerCalcParameters
     */
    public function testGetCalcParameters($param, $value)
    {
        $mock = $this->getMock('GetSky\Geodesics\Geodesics', ['check']);
        $mock->expects($this->exactly(2))->method('check');
        $dis = new ReflectionProperty('GetSky\Geodesics\Geodesics', $param);
        $dis->setAccessible(true);

        $dis->setValue($mock, $value);
        $this->assertSame($value, $mock->$param());
        $dis->setValue($mock, $value);
        $this->assertSame($value, $mock->$param());
    }

    public function testSetFirstPoint()
    {
        $mock = $this->getMock('GetSky\Geodesics\Point',null,[],'',false);
        $this->geodesic->setFirstPoint($mock);
        $this->assertSame($mock, $this->geodesic->getFirstPoint());
    }

    public function testSetSecondPoint()
    {
        $mock = $this->getMock('GetSky\Geodesics\Point',null,[],'',false);
        $this->geodesic->setSecondPoint($mock);
        $this->assertSame($mock, $this->geodesic->getSecondPoint());
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

    public function providerCalcParameters()
    {
        return [
            ['distance', 30],
            ['distance', 345],
            ['bearing', 685],
            ['bearing', 5875],
            ['finishBearing', 3465],
            ['finishBearing', 56]
        ];
    }

    public function providerDistancePoint()
    {
        return [
            [
                [20, 30],
                [34, -34],
                7235204.7533387318,
                167.18321241654618,
                166.60384000025635
            ],
            [[-45, -50], [-45, -50], 0, null, null],
            [
                [68, -90],
                [180, 67],
                17436319.575342514,
                111.99999999999999,
                8.3294178740204185E-15
            ],
            [[-24, 90], [-34, 0], 10001965.729315577, -170.0, -180.0],
            [[180, 45], [180, -90], 14986910.107297322, 180.0, 180.0],
            [
                [-180, 34],
                [45, 76],
                7397040.4275098313,
                -10.778404119271842,
                -140.24458834308729
            ]
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
