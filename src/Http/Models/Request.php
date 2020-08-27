<?php

namespace Ht7\Kernel\Http\Models;

use \Ht7\Kernel\Http\Models\Query;

/**
 * Description of Request
 *
 * @author Thomas Pluess
 */
class Request
{

    protected $gatewayInterface;
    protected $method;
    protected $protocol;
    protected $query;
    protected $queryString;
    protected $remoteAddr;
    protected $remotePort;
    protected $scheme;
    protected $time;
    protected $timeFloat;
    protected $uri;
    protected $vLinks;

    public function __construct()
    {
        $this->vLinks = [
            'GATEWAY_INTERFACE' => 'gatewayInterface',
            'QUERY_STRING' => 'queryString',
            'REMOTE_ADDR' => 'remoteAddr',
            'REMOTE_PORT' => 'remotePort',
            'REQUEST_METHOD' => 'method',
            'REQUEST_SCHEME' => 'scheme',
            'REQUEST_TIME' => 'time',
            'REQUEST_TIME_FLOAT' => 'timeFloat',
            'REQUEST_URI' => 'uri'
        ];

        $this->load($_SERVER);

        $this->query = new Query($_REQUEST);
    }

    public function getGatewayInterface()
    {
        return $this->gatewayInterface;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getQueryString()
    {
        return $this->queryString;
    }

    public function getRemoteAddr()
    {
        return $this->remoteAddr;
    }

    public function getRemotePort()
    {
        return $this->remotePort;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getTimeFloat()
    {
        return $this->timeFloat;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function setGatewayInterface(string $gatewayInterface)
    {
        $this->gatewayInterface = $gatewayInterface;
    }

    public function setMethod(string $method)
    {
        if (in_array($method, ['GET', 'POST'])) {
            $this->method = $method;
        } else {
            $e = 'The method must be "GET" or "POST", found: ' . $method;

            throw \InvalidArgumentException($e);
        }
    }

    public function setQueryString(string $queryString)
    {
        $this->queryString = $queryString;
    }

    public function setRemoteAddr(string $remoteAddr)
    {
        $this->remoteAddr = $remoteAddr;
    }

    public function setRemotePort(string $remotePort)
    {
        $this->remotePort = $remotePort;
    }

    public function setScheme(string $scheme)
    {
        if (in_array($scheme, ['http', 'https'])) {
            $this->scheme = $scheme;
        } else {
            $e = 'The scheme must be "http" or "https", found: ' . $scheme;

            throw \InvalidArgumentException($e);
        }
    }

    public function setTime(string $time)
    {
        $this->time = $time;
    }

    public function setTimeFloat(string $timeFloat)
    {
        $this->timeFloat = $timeFloat;
    }

    public function setUri(string $uri)
    {
        $this->uri = $uri;
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
