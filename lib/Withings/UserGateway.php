<?php

namespace Withings;

class UserGateway extends EndpointGateway{
    
    /**
     * Get user profile depending on user param ( a withings account can manage multiple users
     *
     * @param String $user
     * @return Data
     */
    public function getProfile( $user = null )
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");
        return $this->makeApiRequest( 'measure?action=getbyuserid&userid=' . $user );
    }
    
    /**
     * Get user measure depending on user param ( a withings account can manage multiple users
     *
     * @param String $user
     * @return Data
     */
    public function getMeasures( $user = null )
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");
        return $this->makeApiRequest( 'measure?action=getmeas&userid=' . $user );
    }
}
?>