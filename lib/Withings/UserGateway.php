<?php

namespace Withings;

class UserGateway extends EndpointGateway{
    
    /**
     * Get user profile depending on user param ( a withings account can manage multiple users
     *
     * @param String $user
     * @return Data
     */
    public function getProfile( $user )
    {
        return $this->makeApiRequest( '?userid=' . $user );
    }
}
?>