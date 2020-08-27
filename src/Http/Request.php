<?php

namespace Ht7\Kernel\Http;

use \Ht7\Kernel\Http\Models\Http as HttpModel;
use \Ht7\Kernel\Http\Models\Request as RequestModel;
use \Ht7\Kernel\Http\Models\Server as ServerModel;

/**
 * Description of Request
 *
 * @author Thomas Pluess
 */
class Request
{

    protected $http;
    protected $isChecked;
    protected $isValid;
    protected $request;
    protected $server;

    public function __construct()
    {
        $this->isChecked = false;
        $this->isValid = false;

        $this->http = new HttpModel();
        $this->request = new RequestModel();
        $this->server = new ServerModel();
    }

    public function getHttp()
    {
        return $this->http;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function isChecked()
    {
        return $this->isChecked;
    }

    public function isValid()
    {
        return $this->isValid;
    }

}
