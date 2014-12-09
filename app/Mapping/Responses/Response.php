<?php namespace App\Mapping\Responses;

use App\Mapping\Exceptions\CannotResolveContentTypeException;
use BadMethodCallException;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\App;

/**
 * Class Response
 * @property mixed httpResponse
 * @package App\Mapping\Responses
 */
class Response {

    /**
     * Response object
     *
     * @var null
     */
    protected $response;

    /**
     * Http response, used for images
     *
     * @var
     */
    protected $httpResponse;

    /**
     * Constructor
     *
     * @param null $response
     */
    function __construct($response = null)
    {
        $this->response = $response;

        $this->httpResponse = App::make(HttpResponse::class);
    }

    /**
     * Get Response Headers
     *
     * @return mixed
     */
    public function getHeaders()
    {
        if ( ! $this->classHasMethod(__FUNCTION__))
            throw new BadMethodCallException("Class Does not have " . __FUNCTION__ . " Method");

        return $this->response->getHeaders();
    }

    /**
     * Get Response Body
     *
     * @return mixed
     */
    public function getBody()
    {
        if ( ! $this->classHasMethod(__FUNCTION__))
            throw new BadMethodCallException("Class Does not have " . __FUNCTION__ . " Method");

        return $this->response->getBody();
    }

    /**
     * Get the response in the asked format
     *
     * @param string $outputFormat
     *
     * @return \Illuminate\Http\Response|string
     */
    public function get($outputFormat = 'json')
    {
        if ($this->isAnImage()) return $this->toImage();

        if ($outputFormat === 'array') return $this->toArray();

        return $this->toString();
    }

    /**
     * Find out if response is an image
     *
     * @return bool
     */
    protected function isAnImage()
    {
        return (preg_match('/^image\/.*/i', $this->getContentType()) >= 1);
    }

    /**
     * Get Response content type
     *
     * @throws CannotResolveContentTypeException
     * @return mixed
     */
    protected function getContentType()
    {
        if ( ! is_array($this->getHeaders())
            or ! array_key_exists('Content-Type', $this->getHeaders())
            or ! is_array($this->getHeaders()['Content-Type'])
        )
            throw new CannotResolveContentTypeException('Cannot determine content type');

        return $this->getHeaders()['Content-Type'][0];
    }

    /**
     * Return image response
     *
     * @return \Illuminate\Http\Response
     */
    protected function toImage()
    {
        $response = $this->httpResponse->setContent($this->getBody());

        $response->header('Content-Type', $this->getContentType());

        return $response;
    }

    /**
     * Return response as an array
     *
     * @return array
     */
    protected function toArray()
    {
        return (array) json_decode($this->toString(), true);
    }

    /**
     * Find out if class has method
     *
     * @param $method
     *
     * @return bool
     */
    private function classHasMethod($method)
    {
        return in_array($method, get_class_methods($this->response));
    }

    /**
     * Return string method
     * @return string
     */
    protected function toString()
    {
        return (string) $this->getBody();
    }
}
