<?php

declare(strict_types=1);

namespace Imi\Rpc\Client\Pool;

use Imi\Bean\BeanFactory;
use Imi\Pool\BaseSyncPool;
use Imi\Pool\Interfaces\IPoolResource;
use Imi\Pool\TUriResourceConfig;

/**
 * 同步RPC连接池.
 */
class RpcClientSyncPool extends BaseSyncPool
{
    use TUriResourceConfig;

    /**
     * 资源类.
     */
    protected string $resource = RpcClientResource::class;

    /**
     * @param \Imi\Pool\Interfaces\IPoolConfig $config
     * @param mixed                            $resourceConfig
     */
    public function __construct(string $name, \Imi\Pool\Interfaces\IPoolConfig $config = null, $resourceConfig = null)
    {
        parent::__construct($name, $config, $resourceConfig);
        $this->initUriResourceConfig();
    }

    /**
     * {@inheritDoc}
     */
    protected function createResource(): IPoolResource
    {
        return $this->createNewResource();
    }

    /**
     * {@inheritDoc}
     */
    public function createNewResource(): IPoolResource
    {
        $config = $this->getNextResourceConfig();

        return new $this->resource($this, BeanFactory::newInstance($config['clientClass'], $config));
    }
}
