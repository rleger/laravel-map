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
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    public function setOutputFormat($output_format)
    {
        $this->output_format = $output_format;

        return $this;
    }

    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }
    /**
     * Run the query
     *
     * @return string
     */
    protected function runQuery()
    {
        return $this->adapter->get($this->query);
    }
}
