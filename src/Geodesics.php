<?php
namespace GetSky\Geodesics;

class Geodesics {

    private $first;
    private $second;

    public function distance(array $first, array $second) {
        $distance = '100';
        return $distance;
    }

    public function setFirstPoint($longitude, $latitude) {
        if ($longitude > 180 || $longitude < -180) {
            throw new \Exception("Incorrect value of longitude");
        }
        if ($latitude > 90 || $latitude < -90) {
            throw new \Exception("Incorrect value of longitude");
        }
        $this->first = [$longitude, $latitude];
    }

    public function getFirstPoint() {
        return [$this->first[0], $this->first[1]];
    }

    public function setSecondPoint($longitude, $latitude) {
        if ($longitude > 180 || $longitude < -180) {
            throw new \Exception("Incorrect value of longitude");
        }
        if ($latitude > 90 || $latitude < -90) {
            throw new \Exception("Incorrect value of longitude");
        }
        $this->second = [$longitude, $latitude];
    }

    public function getSecondPoint() {
        return [$this->second[0], $this->second[1]];
    }
}
