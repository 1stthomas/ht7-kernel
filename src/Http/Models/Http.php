<?php

namespace Ht7\Kernel\Http\Models;

/**
 * Description of Server
 *
 * @author Thomas Pluess
 */
class Http
{

    protected $accept;
    protected $acceptEncoding;
    protected $acceptLanguage;
    protected $cacheControl;
    protected $connection;
    protected $host;
    protected $upgradeInsecureRequests;
    protected $userAgent;
    protected $vLinks;

    public function __construct()
    {
        $this->vLinks = [
            'HTTP_ACCEPT' => 'accept',
            'HTTP_ACCEPT_ENCODING' => 'acceptEncoding',
            'HTTP_ACCEPT_LANGUAGE' => 'acceptLanguage',
            'HTTP_CACHE_CONTROL' => 'cacheControl',
            'HTTP_CONNECTION' => 'connection',
            'HTTP_HOST' => 'host',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => 'upgradeInsecureRequests',
            'HTTP_USER_AGENT' => 'userAgent',
        ];

        $this->load($_SERVER);
    }

    public function getAccept()
    {
        return $this->accept;
    }

    public function getAcceptEncoding()
    {
        return $this->acceptEncoding;
    }

    public function getAcceptLanguage()
    {
        return $this->acceptLanguage;
    }

    public function getCacheControl()
    {
        return $this->cacheControl;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getUpgradeInsecureRequests()
    {
        return $this->upgradeInsecureRequests;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setAccept($accept)
    {
        $this->accept = $accept;
    }

    public function setAcceptEncoding($acceptEncoding)
    {
        $this->acceptEncoding = $acceptEncoding;
    }

    public function setAcceptLanguage($acceptLanguage)
    {
        $this->acceptLanguage = $acceptLanguage;
    }

    public function setCacheControl($cacheControl)
    {
        $this->cacheControl = $cacheControl;
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function setUpgradeInsecureRequests($upgradeInsecureRequests)
    {
        $this->upgradeInsecureRequests = $upgradeInsecureRequests;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    protected function load($data)
    {
        foreach ($this->vLinks as $reqName => $vName) {
            if (empty($data[$reqName])) {
//                $e = 'The Request is missing ' . $reqName;
//
//                throw new \InvalidArgumentException($e);
            } else {
                $method = 'set' . ucfirst($vName);

                $this->{$method}($data[$reqName]);
            }
        }
    }

}
