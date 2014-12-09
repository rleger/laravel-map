<?php namespace App\Mapping\MappingProviders;

use App\Mapping\MappingProvider;

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
    function __construct($adapter, $language, $region, $api_key = null, $output_format = 'json')
    {
        $this->adapter = $adapter;

        $this->language = $language;

        $this->region = $region;

        $this->api_key = $api_key ? $api_key : 'AIzaSyDBqIrgIFq0xaTxPtVCbnEHzIVzcTZ-9r0';

        $this->output_format = $output_format;
    }

    /**
     * Get an itinerary
     *
     * @param $from
     * @param $to
     *
     * @return mixed
     */
    public function itinerary($from, $to)
    {
        $this->service = 'directions';

        $this->buildQuery([
            'origin'      => $from,
            'destination' => $to,
            'sensor'      => 'false'
        ]);

        $this->response = $this->runQuery();

        return $this;
    }

    public function staticMap($address, $options = [])
    {
        $this->service = 'staticmap';

        // We need to clear output_format otherwise 'json' will be appended to the url
        $this->output_format = null;

        // Using default values, can be overridden in $options
        $this->buildQuery(array_merge([
            'center' => $address,
            'size'  => '512x512',
            'zoom' => '14',
            'maptype' => 'roadmap',
            'sensor' => 'false'
            ], $options));

        $this->response = $this->runQuery();

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

        $this->response = $this->runQuery();

        return $this;
    }

    /**
     * get response in a given format
     *
     * @param string $format
     *
     * @return mixed
     */
    public function get($format = 'json')
    {
        if ($this->responseIsImage()) return $this->toImage();

        if ($format === 'array') return $this->toArray();

        return (string) $this->response->getBody();
    }

    protected function responseIsImage()
    {
        return (preg_match('/^image\/.*/i', $this->getContentType()) >= 1);
    }

    protected function getContentType()
    {
        return $this->response->getHeaders()['Content-Type'][0];
    }

    protected function toImage()
    {
        $response = \Response::make($this->response->getBody(), '200');

        $response->header('Content-Type', $this->getContentType());

        return $response;
    }

    /**
     * Get response as array
     *
     * @return mixed
     */
    protected function toArray()
    {
        return json_decode((string) $this->response->getBody(), true);
    }

    /**
     * Build the query
     *
     * @param array $parameters
     *
     */
    protected function buildQuery($parameters = [])
    {
        $parameters = array_add($parameters, 'key', $this->api_key);

        $output_format = ($this->output_format) ? '/' . $this->output_format : '' ;

        $this->query = $this->api_url . $this->service . $output_format . '?' . http_build_query($parameters, null, '&', PHP_QUERY_RFC3986);

        return true;
    }
}
