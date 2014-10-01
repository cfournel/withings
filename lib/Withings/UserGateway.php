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
    public function getActivities( $user = null, $startdate, $enddate = null )
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");
        
        if ( $enddate !== null )
            $enddate = "&enddateymd=" . date( "Y-m-d" , $enddate );
        
        return $this->makeApiRequest( 'v2/measure?action=getactivity&userid=' . $user . "&startdateymd=" . date ( "Y-m-d", $startdate ) . $enddate );
    }
    
    /**
     * Request heart data from the API and call the method which saves it locally
     *
     * @param WithingsAccount $withingsAccount
     */
    private function gettHeart($user = null)
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");
        
        return $this->makeApiRequest( 'measure?action=getmeas&userid=' . $user . "&devtype=4" );
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

    /**
     * Create notification subscribe
     *
     * @param String $user
     * @param String $callback
     * @param String $comment ( Optional )
     * @return Data
     */
    public function createNotification( $user, $callback, $comment = "A notifiaction for Withings", $deviceType = null)
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");

        if ( $callback == null )
            throw new \Exception("No callback for notification defined");

        return $this->makeApiRequest( 'notify?action=subscribe&callbackurl=' . rawurlencode( $callback ) . '&comment=' . rawurlencode($comment) . '&appli' . $deviceType  . '&userid=' . $user );
    }

    /**
     * Get notification expire date
     *
     * @param String $user
     * @param String $callback
     * @return Data
     */
    public function getNotificationInfo( $user, $callback)
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");

        if ( $callback == null )
            throw new \Exception("No callback for notification defined");
        
        return $this->makeApiRequest( 'notify?action=get&callbackurl=' . rawurlencode( $callback ) . '&userid=' . $user );
    }

    /**
     * List notifications for a given user
     *
     * @param String $user
     * @return Data
     */
    public function listNotifications( $user = null )
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");

        return $this->makeApiRequest( 'notify?action=list&userid=' . $user );
    }

    /**
     * Remove notification for a given user
     *
     * @param String $user
     * @param String $callback
     * @param String $comment ( Optional )
     * @return Data
     */
    public function removeNotification( $user = null, $callback = null )
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");

        if ( $callback == null )
            throw new \Exception("No callback for notification defined");

        return $this->makeApiRequest( 'notify?action=revoke&callbackurl=' . rawurlencode( $callback ) . '&userid=' . $user );
    }
}
?>
?>
?>
?>
