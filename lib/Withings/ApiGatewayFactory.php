<?php
namespace Withings;

class ApiGateway{

    
   /*
    * Implementation of Withings API based on lusitanian withings implementation
    * 
    * PHP Version 5.4
    *
    * @author     Charles-Antoine FOURNEL <charles@huitiemesens.fr>
    * @copyright  Copyright (c) 2014 Huitieme sens
    * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
    */


use \OAuth\Common\Consumer\Credentials;
use \OAuth\ServiceFactory;

class ApiGatewayFactory
{
    /**
     * @var string
     */
    protected $consumerKey;

    /**
     * @var string
     */
    protected $consumerSecret;

    /**
     * @var string (default: '-')
     */
    protected $userID = '-';
    
    /**
     * @var string (default: 'json')
     */
    protected $responseFormat = 'json';

    /**
     * @var \OAuth\OAuth1\Service\ServiceInterface
     */
    protected $service;
    
    /**
     * @var \OAuth\Common\Storage\TokenStorageInterface
     */
    protected $storageAdapter;
    
    /**
     * @var string
     */
    protected $callbackURL;

    /**
     * @var \OAuth\Common\Http\Client\ClientInterface
     */
    protected $httpClient;

    /**
     * Set consumer credentials
     * 
     * @access public
     * @param string $consumer_key Application consumer key for Withings API
     * @param string $consumer_secret Application secret
     * @return \Withings\ApiGatewayFactory
     */
    public function setCredentials($consumer_key, $consumer_secret)
    {
        $this->consumerKey    = $consumer_key;
        $this->consumerSecret = $consumer_secret;
        return $this;
    }

    /**
     * Set storage adapter.
     * 
     * @access public
     * @param \OAuth\Common\Storage\TokenStorageInterface $adapter
     * @return \Withings\ApiGatewayFactory
     */
    public function setStorageAdapter(\OAuth\Common\Storage\TokenStorageInterface $adapter)
    {
        $this->storageAdapter = $adapter;
        return $this;
    }

    /**
     * Get storage adapter.
     * 
     * @access public
     * @return \OAuth\Common\Storage\TokenStorageInterface
     */
    public function getStorageAdapter()
    {
        return $this->storageAdapter;
    }

    /**
     * Set response format.
     * 
     * @access public
     * @param string $format Response format (json or xml) to use in API calls
     * @throws \Withings\Exception
     * @return \Withings\ApiGatewayFactory
     */
    public function setResponseFormat($format)
    {
        if (!in_array($format, array('json', 'xml'))) {
            throw new Exception("Reponse format must be one of 'json', 'xml'");
        }
        $this->responseFormat = $format;
        return $this;
    }

    /**
     * Set callback URL.
     * 
     * @access public
     * @param string $url
     * @return \Withings\ApiGatewayFactory
     */
    public function setCallbackURL($url)
    {
        $this->callbackURL = $url;
        return $this;
    }

    /**
     * Set Withings user id for API calls
     *
     * @access public
     * @param string $id
     * @return \Withings\ApiGatewayFactory
     */
    public function setUserID($id)
    {
        $this->userID = $id;
        return $this;
    }

    /**
     * Set HTTP Client library for Withings service.
     *
     * @param  \OAuth\Common\Http\Client\ClientInterface $client
     * @return \Withings\ApiGatewayFactory
     */
    public function setHttpClient(\OAuth\Common\Http\Client\ClientInterface $client)
    {
        $this->httpClient = $client;
        return $this;
    }

    public function getAuthenticationGateway()
    {
        $gateway = new AuthenticationGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

    public function getUserGateway()
    {
        $gateway = new UserGateway;
        $this->injectGatewayDependencies($gateway);
        return $gateway;
    }

    protected function injectGatewayDependencies($gateway)
    {
        $gateway->setService($this->getService())
                ->setResponseFormat($this->responseFormat)
                ->setUserID($this->userID);
    }

    /**
     * Get Withings service
     *
     * @access protected
     * @throws \Withings\Exception
     * @return \OAuth\OAuth1\Service\ServiceInterface
     */
    protected function getService()
    {
        if (!$this->consumerKey) {
            throw new Exception('Empty consumer key.');
        }
        if (!$this->consumerSecret) {
            throw new Exception('Empty consumer secret.');
        }
        if (!$this->callbackURL) {
            throw new Exception('Empty callback URL.');
        }
        if (!$this->storageAdapter) {
            throw new Exception('Missing storage adapter.');
        }

        if (!$this->service) {
            $credentials = new Credentials(
                $this->consumerKey,
                $this->consumerSecret,
                $this->callbackURL
            );

            $factory = new ServiceFactory();

            if ($this->httpClient) {
                $factory->setHttpClient($this->httpClient);
            }

            $this->service = $factory->createService('Withings', $credentials, $this->storageAdapter);
        }

        return $this->service;
    }
}

}
?>