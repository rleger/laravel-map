<?php namespace App\Mapping;

use Ivory\HttpAdapter\HttpAdapterInterface;

interface MappingProvider {

    function __construct(HttpAdapterInterface $adapter, $language = null, $region = null, $api_key = null, $output_format = 'json');

    public function itinerary($from, $to);

    public function geocode($address);

    public function staticmap($address);
}
