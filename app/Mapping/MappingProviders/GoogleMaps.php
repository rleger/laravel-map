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

        $query = $this->buildQuery([
            'origin'      => $from,
            'destination' => $to,
            'sensor'      => 'false'
        ]);

        $this->response = $this->runQuery($query);

        return $this;
    }

    public function staticMap($address)
    {
        // center=144+route+de+toussieu+69800&zoom=24&size=512x512&maptype=roadmap&markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318&markers=color:red%7Ccolor:red%7Clabel:C%7C40.718217,-73.998284&sensor=false&key=AIzaSyDBqIrgIFq0xaTxPtVCbnEHzIVzcTZ-9r0
        $this->service = 'staticmap';

        $this->output_format = null;

        $query = $this->buildQuery([
            'center' => $address,
            'size'  => '512x512'
            ]);

        return $this->adapter->get($query);




//******************************************

        $this->response = $this->runQuery($query);

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

        $query = $this->buildQuery(['address' => $address]);

        $this->response = $this->runQuery($query);

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
        if ($format === 'array')
        {
            return $this->toArray();
        }

        return $this->response;
    }

    /**
     * Get response as array
     *
     * @return mixed
     */
    protected function toArray()
    {
        return json_decode($this->response, true);
    }

    /**
     * Build the query
     *
     * @param array $parameters
     *
     * @return string
     */
    protected function buildQuery($parameters = [])
    {
        $parameters = array_add($parameters, 'key', $this->api_key);

        $output_format = ($this->output_format) ? '/' . $this->output_format : '' ;

        $this->query = $this->api_url . $this->service . $output_format . '?' . http_build_query($parameters, null, '&', PHP_QUERY_RFC3986);

        return $this->query;
    }
}
