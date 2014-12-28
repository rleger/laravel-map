<?php namespace App\Mapping\MappingProviders;

use App\Mapping\MappingProvider;
use App\Mapping\Responses\Response;
use App\Mapping\Adapters\Adapter;

/**
 * Class GoogleMaps
 * @package App\Mapping\MappingProviders
 */
class GoogleMaps extends MappingService implements MappingProvider {

    /**
     * API Base URL
     *
     * @var string
     */
    protected $api_url = 'https://maps.googleapis.com/maps/api/';

    /**
     * Constructor
     *
     * @param        $adapter
     * @param        $language
     * @param        $region
     * @param null   $api_key
     * @param string $output_format
     */
    function __construct(Adapter $adapter, $language = null, $region = null, $api_key = null, $output_format = 'json')
    {
        $this->adapter = $adapter;

        $this->language = $language;

        $this->region = $region;

        $this->api_key = $api_key ? $api_key : \Config::get('app.GoogleMap.API_Key');

        $this->output_format = $output_format;
    }

    /**
     * Get an itinerary
     *
     * @param       $from
     * @param       $to
     *
     * @param array $options
     *
     * @return mixed
     */
    public function itinerary($from, $to, $options = [])
    {
        $this->service = 'directions';

        $this->buildQuery(array_merge([
            'origin'      => $from,
            'destination' => $to,
            'sensor'      => 'false'
        ], $options));

        $this->response = new Response($this->runQuery());

        return $this;
    }

    /**
     * Reverse geocoding
     *
     * @param       $lat
     * @param       $long
     *
     * @param array $options
     *
     * @return $this
     */
    public function reverse($lat, $long, $options = [])
    {
        $this->service = 'geocode';

        $this->buildQuery(array_merge(['latlng' => "$lat,$long"], $options));

        $this->response = new Response($this->runQuery());

        return $this;
    }

    /**
     * Returns the response object as json
     *
     * @return mixed
     */
    public function __toString()
    {
        return $this->response->get('json');
    }

    /**
     * Get a Static Map
     *
     * @param       $address
     * @param array $options
     *
     * @return $this
     */
    public function staticMap($address, $options = [])
    {
        $this->service = 'staticmap';

        // We need to clear output_format otherwise 'json' will be appended to the url
        $this->output_format = null;

        // Using default values, can be overridden in $options
        $this->buildQuery(array_merge([
            'center'  => $address,
            'size'    => '512x512',
            'zoom'    => '14',
            'maptype' => 'roadmap',
            'sensor'  => 'false'
        ], $options));

        $this->response = new Response($this->runQuery());

        return $this;
    }

    /**
     * Geocode an address
     *
     * @param $address
     *
     * @return mixed
     */
    public function geocode($address)
    {
        $this->service = 'geocode';

        $this->buildQuery(['address' => $address]);

        $this->response = new Response($this->runQuery());

        return $this;
    }

    /**
     * Get response in the asked format
     *
     * @param string $format
     *
     * @return mixed
     */
    public function get($format = 'json')
    {
        return $this->response->get($format);
    }

    /**
     * Build the query
     *
     * @param array $parameters
     *
     * @return bool
     */
    protected function buildQuery($parameters = [])
    {
        $parameters = array_add($parameters, 'key', $this->api_key);

        $output_format = ($this->output_format) ? '/' . $this->output_format : '';

        $this->query = $this->api_url . $this->service . $output_format . '?' . http_build_query($parameters, null, '&', PHP_QUERY_RFC3986);

        return true;
    }
}
