<?php

class Cargo	{

    private $trackingId;
    private $origin;
    private $destination;

    function __construct ($trackingId, $origin, $destination)	{
	    if (is_null ($trackingId)) throw new Exception ('Tracking ID cannot be null');
	    if (is_null ($origin)) throw new Exception ('Origin cannot be null');
	    if (is_null ($destination)) throw new Exception ('Destination cannot be null'); 
	    $this->trackingId = $trackingId;
	    $this->origin = $origin;
	    $this->destination = $destination;
    }

    function trackingId()	{
	    return $this->trackingId;
    }

    function origin()	{
	    return $this->origin;
    }

    function destination()	{
	    return $this->destination;
    }

}