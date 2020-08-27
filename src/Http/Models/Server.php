<?php

namespace Ht7\Kernel\Http\Models;

/**
 * Description of Server
 *
 * @author Thomas Pluess
 */
class Server
{

    protected $addr;
    protected $admin;
    protected $comspec;
    protected $contextDocRoot;
    protected $contextPrefix;
    protected $docRoot;
    protected $name;
    protected $path;
    protected $port;
    protected $protocol;
    protected $pathExt;
    protected $scriptName;
    protected $scriptFilename;
    protected $signature;
    protected $software;
    protected $systemRoot;
    protected $winDir;

    public function __construct()
    {
        $this->vLinks = [
            'COMSPEC' => 'comspec',
            'CONTEXT_DOCUMENT_ROOT' => 'contextDocRoot',
            'CONTEXT_PREFIX' => 'contextPrefix',
            'DOCUMENT_ROOT' => 'docRoot',
            'PATH' => 'path',
            'PATHEXT' => 'pathExt',
            'SCRIPT_FILENAME' => 'scriptFilename',
            'SCRIPT_NAME' => 'scriptName',
            'SERVER_ADDR' => 'addr',
            'SERVER_ADMIN' => 'admin',
            'SERVER_NAME' => 'name',
            'SERVER_PORT' => 'port',
            'SERVER_PROTOCOL' => 'protocol',
            'SERVER_SIGNATURE' => 'signature',
            'SERVER_SOFTWARE' => 'software',
            'SystemRoot' => 'systemRoot',
            'WINDIR' => 'winDir',
        ];

        $this->load($_SERVER);
    }

    public function getAddr()
    {
        return $this->addr;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function getComspec()
    {
        return $this->comspec;
    }

    public function getContextDocRoot()
    {
        return $this->contextDocRoot;
    }

    public function getContextPrefix()
    {
        return $this->contextPrefix;
    }

    public function getDocRoot()
    {
        return $this->docRoot;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getPathExt()
    {
        return $this->pathExt;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    public function getScriptFilename()
    {
        return $this->scriptFilename;
    }

    public function getScriptName()
    {
        return $this->scriptName;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public function getSoftware()
    {
        return $this->software;
    }

    public function getSystemRoot()
    {
        return $this->systemRoot;
    }

    public function getWinDir()
    {
        return $this->winDir;
    }

    public function setAddr(string $addr)
    {
        $this->addr = $addr;
    }

    public function setAdmin(string $admin)
    {
        $this->admin = $admin;
    }

    public function setComspec(string $comspec)
    {
        $this->comspec = $comspec;
    }

    public function setContextDocRoot(string $contextDocRoot)
    {
        $this->contextDocRoot = $contextDocRoot;
    }

    public function setContextPrefix(string $contextPrefix)
    {
        $this->contextPrefix = $contextPrefix;
    }

    public function setDocRoot(string $docRoot)
    {
        $this->docRoot = $docRoot;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function setPathExt(string $pathExt)
    {
        $this->pathExt = $pathExt;
    }

    public function setPort(string $port)
    {
        $this->port = $port;
    }

    public function setProtocol(string $protocol)
    {
        $this->protocol = $protocol;
    }

    public function setScriptFilename(string $scriptFilename)
    {
        $this->scriptFilename = $scriptFilename;
    }

    public function setScriptName(string $scriptName)
    {
        $this->scriptName = $scriptName;
    }

    public function setSignature(string $signature)
    {
        $this->signature = $signature;
    }

    public function setSoftware(string $software)
    {
        $this->software = $software;
    }

    public function setSystemRoot(string $systemRoot)
    {
        $this->systemRoot = $systemRoot;
    }

    public function setWinDir(string $winDir)
    {
        $this->winDir = $winDir;
    }

    protected function load(array $data)
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
