<?php
namespace GetSky\Geodesics;

class Geodesics
{

    const WGS84_A = 6378137;
    const WGS84_B = 6356752.31425;

    const MAX_LONG = 180;
    const MIN_LONG = -180;
    const MIN_LAT = -90;
    const MAX_LAT = 90;

    private $first;
    private $second;

    private $bearing;
    private $finishBearing;
    private $distance;

    public function distance()
    {
        $this->check();
        return $this->distance;
    }

    public function bearing()
    {
        $this->check();
        return $this->bearing;
    }

    public function finishBearing()
    {
        $this->check();
        return $this->finishBearing;
    }

    protected function calc()
    {
        $lon1 = (M_PI / 180) * $this->first[0];
        $lat1 = (M_PI / 180) * $this->first[1];
        $lon2 = (M_PI / 180) * $this->second[0];
        $lat2 = (M_PI / 180) * $this->second[1];

        $WGS84_F = 1 / 298.257223563;

        $L = $lon2 - $lon1;

        $tanU1 = (1 - $WGS84_F) * tan($lat1);
        $cosU1 = 1 / sqrt((1 + $tanU1 * $tanU1));
        $sinU1 = $tanU1 * $cosU1;

        $tanU2 = (1 - $WGS84_F) * tan($lat2);
        $cosU2 = 1 / sqrt((1 + $tanU2 * $tanU2));
        $sinU2 = $tanU2 * $cosU2;

        $lon = $L;
        $i = 0;

        do {
            $sinLon = sin($lon);
            $cosLon = cos($lon);

            $sinSqSigma =
                ($cosU2 * $sinLon) * ($cosU2 * $sinLon) +
                ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLon) *
                ($cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLon);
            $sinSigma = sqrt($sinSqSigma);

            if ($sinSigma == 0) {
                $this->distance = 0;
                $this->bearing = null;
                $this->finishBearing = null;
                return;
            }

            $cosSigma = $sinU1 * $sinU2 + $cosU1 * $cosU2 * $cosLon;
            $sigma = atan2($sinSigma, $cosSigma);
            $sinAlfa = $cosU1 * $cosU2 * $sinLon / $sinSigma;
            $cosSqAlfa = 1 - $sinAlfa * $sinAlfa;
            $cos2SigmaM = $cosSigma - 2 * $sinU1 * $sinU2 / $cosSqAlfa;
            $cos2SigmaMQ = $cos2SigmaM * $cos2SigmaM;

            if (is_nan($cos2SigmaM)) {
                $cos2SigmaM = 0;
            }

            $C = $WGS84_F / 16 * $cosSqAlfa * (4 + $WGS84_F * (4 - 3 * $cosSqAlfa));
            $lonC = $lon;
            $cof = $sigma + $C * $sinSigma * ($cos2SigmaM + $C * $cosSigma * (-1 + 2 * $cos2SigmaMQ));
            $lon = $L + (1 - $C) * $WGS84_F * $sinAlfa * $cof;

        } while (abs($lon - $lonC) > 1e-12 && ++$i < 200);


        if ($i >= 200) {
            throw new \Exception('Formula failed to converge');
        }

        $uSq =
            $cosSqAlfa *
            (self::WGS84_A * self::WGS84_A - self::WGS84_B * self::WGS84_B) / (self::WGS84_B * self::WGS84_B);

        $A = 1 + $uSq / 16384 * (4096 + $uSq * (-768 + $uSq * (320 - 175 * $uSq)));
        $B = $uSq / 1024 * (256 + $uSq * (-128 + $uSq * (74 - 47 * $uSq)));
        $cof2 = $B / 6 * $cos2SigmaM * (-3 + 4 * $sinSigma * $sinSigma) * (-3 + 4 * $cos2SigmaMQ);
        $deltaSigma = $B * $sinSigma * ($cos2SigmaM + $B / 4 * ($cosSigma * (-1 + 2 * $cos2SigmaMQ) - $cof2));

        $this->distance = self::WGS84_B * $A * ($sigma - $deltaSigma);

        $this->bearing = atan2(
                $cosU2 * $sinLon,
                $cosU1 * $sinU2 - $sinU1 * $cosU2 * $cosLon
            ) / (M_PI / 180);
        $this->finishBearing = atan2(
                $cosU1 * $sinLon,
                -$sinU1 * $cosU2 + $cosU1 * $sinU2 * $cosLon
            ) / (M_PI / 180);
    }

    protected function check()
    {
        if ($this->distance === null) {
            $this->calc();
        }
    }

    public function setFirstPoint($longitude, $latitude)
    {
        $this->validation($longitude, $latitude);
        $this->first = [$longitude, $latitude];
    }

    public function getFirstPoint()
    {
        return $this->first;
    }

    public function setSecondPoint($longitude, $latitude)
    {
        $this->validation($longitude, $latitude);
        $this->second = [$longitude, $latitude];
    }

    public function getSecondPoint()
    {
        return $this->second;
    }

    protected function validation($longitude, $latitude)
    {
        if ($longitude > self::MAX_LONG || $longitude < self::MIN_LONG) {
            throw new \Exception("Incorrect value of longitude");
        }
        if ($latitude > self::MAX_LAT || $latitude < self::MIN_LAT) {
            throw new \Exception("Incorrect value of latitude");
        }
    }
}
