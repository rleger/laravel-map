<?php namespace App\Mapping;

interface MappingProvider {

    function __construct($adapter, $language, $region, $api_key, $output_format);

    public function itinerary($from, $to);

    public function geocode($address);

    public function staticmap($address);

}