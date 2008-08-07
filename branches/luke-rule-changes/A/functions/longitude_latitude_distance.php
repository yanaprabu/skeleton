<?php

$lat1 = $_REQUEST['lat1'];
$long1 = $_REQUEST['long1'];
$lat2 = $_REQUEST['lat2'];
$long2 = $_REQUEST['long2'];

$d = distance_cartesian($lat1, $long1, $lat2, $long2);
print "cartesian distance: $d\n";

$d = distance_cartesian_modified($lat1, $long1, $lat2, $long2);
print "cosine modified cartesian distance: $d\n";

$d = distance_spherical($lat1, $long1, $lat2, $long2);
print "spherical trig distance: $d\n";

#cartesian distance
function distance_cartesian($lat1, $long1, $lat2, $long2) {
    $d =  sqrt( pow($long1-$long2, 2) + pow($lat1-$lat2, 2) );
    return $d * 69;
}

# modified cartesian distance
function distance_cartesian_modified($lat1, $long1, $lat2, $long2) {
    $d = sqrt( 
    			pow(
    				($long1 - $long2) 
    				* cos(
    					(($lat1 + $lat2) / 2)  / 180 * M_PI
    					)
    			, 2) 
    			+ pow(($lat1-$lat2), 2) 
    		) * 69;
   return $d;
}

function acos($n) { 
	return atan2( sqrt(1 - $n * $n), $n);
}


# convert degres to radians
function deg2rad($n) {
    return $n / 180 * M_PI;
}

# spherical distance
function distance_spherical($lat1, $long1, $lat2, $long2) {
    $lat1 = $lat1/180 * M_PI;
    $long1 = $long1/180 * M_PI;
    $lat2 = $lat2/180 * M_PI;
    $long2 = $long2/180 * M_PI;

    $a = $long1 - $long2;
    if ($a < 0) {$a = -$a;}
    if ($a > M_PI) {$a = 2 * M_PI;}

    $d = acos(sin($lat2) * sin($lat1) + cos($lat2)*cos($lat1)*cos($a)) * 3958;
    return $d;
}
/*
die "usage disttest lat1 long1 lat2 long2" unless (@ARGV == 4);
my ($lat1, $long1, $lat2, $long2) = @ARGV;

my $d1 = dist(@ARGV);
print "spherical trig distance: $d1\n";

$d2 = cdist(@ARGV);
print "cartesian distance: $d2\n";

$d3 = mcdist(@ARGV);
print "cosine modified cartesian distance: $d3\n";

#cartesian distance
sub cdist {
    my ($lat1, $long1, $lat2, $long2) = @_;
    $h =  sqrt( ($long1-$long2)**2 + ($lat1-$lat2)**2 );
    return $h * 69;
}

# modified cartesian distance
sub mcdist {
    my ($lat1, $long1, $lat2, $long2) = @_;
    # calc hyp

    $h =  sqrt( (($long1-$long2)* cos(deg2rad( ($lat1+$lat2)/2)))**2 + ( ($lat1-$lat2))**2 )*69;

    return $h ;
}

sub acos { 
	#print STDERR " acos param: |@_| \n";
	atan2( sqrt(1-$_[0] * $_[0]), $_[0])
}


# convert degres to radians
sub deg2rad {
    my $n = shift;
    $pi = atan2(1,1) * 4;
    return $n/180 * $pi;
}

# spherical distance
sub dist {

    $pi = atan2(1,1) * 4;

    my @parms = @_;
    my $lat1 = $parms[0]/180 * $pi ;
    my $long1 = $parms[1]/180 * $pi;
    my $lat2 = $parms[2]/180 * $pi;
    my $long2 = $parms[3]/180 * $pi;

    $a = $long1 - $long2;
    if ($a < 0) {$a = -$a;}
    if ($a > $pi) {$a = 2 * $pi;}

    $d = acos(sin($lat2) * sin($lat1) + cos($lat2)*cos($lat1)*cos($a)) * 3958;
    return $d ;
}
*/