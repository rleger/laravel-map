<?php namespace App\Mapping;

use App\Mapping\Adapters\Adapter;

interface MappingProvider {

    function __construct(Adapter $adapter, $language = null, $region = null, $api_key = null, $output_format = 'json');

    public function itinerary($from, $to);

    public function geocode($address);

    public function staticmap($address);
}
