<?php

namespace Goodwords\API;

class Redirector
{
    private $server = [];

    public function __construct($server) {
        $this->server = $server;
    }

    public function run() {
        $url = new Url();

        $url->host       = $this->extractHost($this->server['HTTP_HOST']);
        $url->protocol   = (array_key_exists('HTTPS', $this->server) && $this->server['HTTPS'] == 'on') ? 'https' : 'http';
        $url->requestUri = $this->server['REQUEST_URI'];

        $this->redirect($url->build());
    }

    private function extractHost($httpHost)
    {
        $hostSegments = explode('.', $httpHost);

        $intendedSegments = [];
        for ($i = count($hostSegments)-1; $i >= 0; $i--) {
            if ($hostSegments[$i] == 'redirector') {
                $j = 0;
                while ($j < $i) {
                    $intendedSegments[] = $hostSegments[$j];
                    $j++;
                }
            }
        }

        return implode('.', $intendedSegments);
    }

    private function redirect($url)
    {
        header('Location: '.$url, 302);
    }
}

class Url
{
    public $protocol;

    public $host;

    public $requestUri = '';

    public function build()
    {
        return $this->protocol.'://'.$this->host.$this->requestUri;
    }
}
