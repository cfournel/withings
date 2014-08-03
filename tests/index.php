<?php
  require '../vendor/autoload.php';

  $api = new \Withings\Api("clientid", "clientsecret");
  //$api->resetSession();
  if (!$api->isAuthorized()) {
    $api->initSession();
  }

?>

<pre>
<?= var_dump($api->getProfile()); ?>
</pre>
