## WithingsPHP ##

Basic wrapper for the OAuth-based [Withings](http://withings.com) [REST API](http://oauth.withings.com). See [oauth.withings.com](http://oauth.withings.com/en/api/oauthguide) for details on their OAuth implementation.

Both this library and the Withings API are in **beta**.

This library does not require the PHP OAuth extension. It should work on any server with PHP >= 5.3.

## Installation ##
This package is installable with composer:
    "huitiemesens/withings": "dev-master"

## Usage ##

You need a consumer key and secret. You can obtain them by registering an application at [http://oauth.withings.com](http://oauth.withings.com).

Simple, but full OAuth workflow example with database integration:


* App Authorization


```php

        $factory = new \Withings\ApiGatewayFactory;
        $factory->setCallbackURL( $withings_callback_url );
        $factory->setCredentials( $withings_consumer_key , $withings_consumer_secret ); // these variables come from database

        $adapter    = new \OAuth\Common\Storage\Session();
        $factory->setStorageAdapter($adapter);

        $auth_gateway = $factory->getAuthenticationGateway();
        
        if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
            $auth_gateway->authenticateUser($_GET['oauth_token'], $_GET['oauth_verifier']);

            $storage = $factory->getStorageAdapter();
            $token   = $storage->retrieveAccessToken('Withings');
            
            $this->getUser()->setWithingsToken( $token->getRequestToken() ) ; // Your user entity must have a WithingsToken column
            $this->getUser()->setWithingsTokenSecret( $token->getRequestTokenSecret() ); // Your user entity must have a WithingsTokenSecret column
            $this->getUser()->setWithingsId( $_GET['user_id'] ); // Your user entity must have a WithingsUserId column
            
            $em->persist( $this->getUser() );
            
            $em->flush();

            
        }else
        {
            $auth_gateway->initiateLogin();
        }
```

* Retrieving user profile informations

```php

    public function withingsGetProfile()
    {
        $factory = new \Withings\ApiGatewayFactory;
        $factory->setCallbackURL( $withings_callback_url );
        $factory->setCredentials( $withings_consumer_key , $withings_consumer_secret ); // these variables come from database

                
        $token  = new \OAuth\OAuth1\Token\StdOAuth1Token();
        $token->setRequestToken( $user->getWithingsToken() ); // user credentials
        $token->setRequestTokenSecret( $user->getWithingsTokenSecret() ); // user credentials
        $token->setAccessToken( $user->getWithingsToken() ) // user credentials;
        $token->setAccessTokenSecret( $user->getWithingsTokenSecret() ); // user credentials

        $adapter = new \OAuth\Common\Storage\Memory();
        $adapter->storeAccessToken('Withings', $token);

        $factory->setStorageAdapter($adapter);
        
        $UserGateway        =   $factory->getUserGateway();
        $profile            =   $UserGateway->getProfile( $user->getWithingId() ); // user withings id
        
        print_r ( $profile ) ;
    }
```

Thanks to popthestack/fitbitphp for the code structure.