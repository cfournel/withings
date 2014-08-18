<?php

namespace Withings;

class EndpointGateway {

    /**
     * @var \OAuth\OAuth1\Service\ServiceInterface
     */
    protected $service;

    /**
     * @var string
     */
    protected $responseFormat;

    /**
     * @var string
     */
    protected $userID;

    /**
     * Set Withings service
     *
     * @access public
     * @param \OAuth\OAuth1\Service\ServiceInterface
     * @return \Withings\EndpointGateway
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * Set response format.
     * 
     * @access public
     * @param string $response_format
     * @return \Withings\EndpointGateway
     */
    public function setResponseFormat($format)
    {
        $this->responseFormat = $format;
        return $this;
    }

    /**
     * Set Withings user ids.
     *
     * @access public
     * @param string $id
     * @return \Withings\EndpointGateway
     */
    public function setUserID($id)
    {
        $this->userID = $id;
        return $this;
    }

    /**
     * Make an API request
     *
     * @access protected
     * @param string $resource Endpoint after '.../1/'
     * @param string $method ('GET', 'POST', 'PUT', 'DELETE')
     * @param array $body Request parameters
     * @param array $extraHeaders Additional custom headers
     * @return mixed stdClass for json response, SimpleXMLElement for XML response.
     */
    protected function makeApiRequest($resource, $method = 'GET', $body = array(), $extraHeaders = array())
    {
        $resource   = explode ( "?" , $resource ); // . '.' . $this->responseFormat 
        $path       = $resource[0];
        
        // we explode $resource[1] to get the userid
        $resource   = explode ( "&userid=", $resource[1] );
        $action     = $resource[0];
        $userid     = $resource[1];
        
        if ($method == 'GET' && $body) {
            $path .= '?' . http_build_query($body);
            $body = array();
        }

        $response = $this->service->request($path, $method, $body, $extraHeaders, array("api" => "withings", "action" => $action, "userid" => $userid ) );

        return $this->parseResponse($response);
    }

    /**
     * Parse json or XML response.
     *
     * @access private
     * @return mixed stdClass for json response, SimpleXMLElement for XML response.
     */
    private function parseResponse($response)
    {
        if ($this->responseFormat == 'json') {
            return json_decode($response);
        } elseif ($this->responseFormat == 'xml') {
            return simplexml_load_string($response);
        }

        return $response;
    }
}
