Distances between points on an ellipsoidal earth model
===================================================================

Solution for the distance between points on an ellipsoidal earth model is accurate to within 0.5mm distance, 0.000015″ bearing, on the ellipsoid being used.

```php
$geodesic = new  GetSky\Geodesics\Geodesics();
$geodesic->setFirstPoint(new Point(34.478785, 56.35755));
$geodesic->setFirstPoint(new Point(78.3458685, -46.15445));

$distance = $geodesic->distance();
$bearing = $geodesic->bearing();
$finishBearing = $geodesic->finishBearing();
```
