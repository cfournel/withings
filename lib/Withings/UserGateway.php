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

    /**
     * Create notification subscribe
     *
     * @param String $user
     * @param String $callback
     * @param String $comment ( Optional )
     * @return Data
     */
    public function createNotification( $user = null, $callback = null, $comment = null )
    {
        if ( $user == null )
            throw new \Exception("No Withings User id defined");

        if ( $callback == null )
            throw new \Exception("No callback for notification defined");

        if ( $comment == null )
            $comment = "A notification from withings";

        return $this->makeApiRequest( 'notify?action=subscribe&callbackurl=' . rawurlencode( $callback ) . '&comment=' . rawurlencode($comment) . '&userid=' . $user );
    }

    /**
     * Get notification expire date
     *
     * @param String $user
     * @param String $callback
     * @return Data
     */
    public function getNotificationInfo( $user = null, $callback = null )
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
