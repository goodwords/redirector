<?php

require __DIR__.'/../app/redirector.php';

$redirector = new Goodwords\API\Redirector($_SERVER);

$redirector->run();
