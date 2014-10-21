<?php
use GetSky\Geodesics\Point;

class PointTest extends PHPUnit_Framework_TestCase
{
    const CLASS_NAME = 'GetSky\Geodesics\Point';

    public function testCreatePoint()
    {
        $class = new Point(40, 30);

        $lat = $this->getPrivateProperty(self::CLASS_NAME, 'latitude');
        $lon = $this->getPrivateProperty(self::CLASS_NAME, 'longitude');

        $this->assertSame(40, $lon->getValue($class));
        $this->assertSame(30, $lat->getValue($class));
    }

    public function testGetLatitude()
    {
        $mock = $this->getClassMock();

        $lat = $this->getPrivateProperty(self::CLASS_NAME, 'latitude');
        $lat->setValue($mock, 66);
        $this->assertSame(66, $mock->getLatitude());
        $lat->setValue($mock, 45);
        $this->assertSame(45, $mock->getLatitude());
    }

    public function testGetLongitude()
    {
        $mock = $this->getClassMock();

        $lat = $this->getPrivateProperty(self::CLASS_NAME, 'longitude');
        $lat->setValue($mock, 23);
        $this->assertSame(23, $mock->getLongitude());
        $lat->setValue($mock, 78);
        $this->assertSame(78, $mock->getLongitude());
    }


    public function providerPoint()
    {
        return [
            [24, 43],
            [15, 23]
        ];
    }

    protected function getClassMock()
    {
        return $this->getMockBuilder(self::CLASS_NAME)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
    }

    protected function getPrivateProperty($class, $param)
    {
        $obj = new ReflectionProperty($class, $param);
        $obj->setAccessible(true);
        return $obj;
    }

}
