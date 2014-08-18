<?php

namespace Withings;

class UserGateway extends EndpointGateway{
    
    /**
     * Get user body depending on user param ( a withings account can manage multiple users
     *
     * @param String $user
     * @return Data
     */
    public function getBody( $user = null, $lastUpdate = '' )
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");
        
        if ( !empty ( $lastUpdate ) )
            $lastUpdate = '&lastupdate=' . strtotime( $lastUpdate );
        
        return $this->makeApiRequest( 'measure?action=getmeas&userid=' . $user . $lastUpdate );
    }
    
    /**
     * Get user activities depending on user param ( a withings account can manage multiple users )
     *
     * @param String $user
     * @return Data
     */
    public function getActivities( $user = null, $lastUpdate = '' )
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");
        
        if ( !empty ( $lastUpdate ) )
            $lastUpdate = '&lastupdate=' . strtotime( $lastUpdate );
        
        return $this->makeApiRequest( 'v2/measure?action=getactivity&userid=' . $user . $lastUpdate );
    }
    
    /**
     * Get user sleeps depending on user param ( a withings account can manage multiple users )
     *
     * @param String $user
     * @return Data
     */
    public function getSleeps( $user = null, $lastUpdate = '' )
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");
        
        if ( !empty ( $lastUpdate ) )
            $lastUpdate = '&lastupdate=' . strtotime( $lastUpdate );
        
        return $this->makeApiRequest( 'v2/sleep?action=get&userid=' . $user . $lastUpdate );
    }
}
?>