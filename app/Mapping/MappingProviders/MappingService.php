<?php namespace App\Mapping\MappingProviders;

/**
 * Class MappingService
 * @package App\Mapping\MappingProviders
 */
abstract class MappingService {

    /**
     * API base url
     * @var
     */
    protected $api_url;

    /**
     * API Key
     * @var
     */
    protected $api_key;

    /**
     * Query
     * @var
     */
    protected $query;

    /**
     * Language to return the restults in ('fr')
     * @var null
     */
    protected $language = null;

    /**
     * Region to search ('fr')
     * @var null
     */
    protected $region = null;

    /**
     * Adapter to process the query (ex. GuzzleHttp)
     * @var
     */
    protected $adapter;

    /**
     * Output format (json, xml..)
     * @var
     */
    protected $output_format;

    /**
     * Service to execute (geocode, reverse, directions...)
     * @var
     */
    protected $service;

    /**
     * Response
     * @var
     */
    protected $response;

    /**
     * Set the API Key
     *
     * @param mixed $api_key
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * Run the query
     *
     * @param $query
     *
     * @return string
     */
    protected function runQuery($query)
    {
        return (string) $this->adapter->get($query)->getBody();
    }
}
