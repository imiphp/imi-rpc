<?php
namespace Imi\Rpc\Client\Pool;

use Imi\App;
use Imi\Bean\BeanFactory;
use Imi\Pool\BaseSyncPool;
use Imi\Pool\TUriResourceConfig;

/**
 * 同步RPC连接池
 */
class RpcClientSyncPool extends BaseSyncPool
{
    use TUriResourceConfig;

    /**
     * 资源类
     *
     * @var string
     */
    protected $resource = RpcClientResource::class;

    public function __construct(string $name, \Imi\Pool\Interfaces\IPoolConfig $config = null, $resourceConfig = null)
    {
        parent::__construct($name, $config, $resourceConfig);
        $this->initUriResourceConfig();
    }

    /**
     * 创建资源
     * @return \Imi\Pool\Interfaces\IPoolResource
     */
    protected function createResource(): \Imi\Pool\Interfaces\IPoolResource
    {
        $config = $this->getNextResourceConfig();
        return new $this->resource($this, BeanFactory::newInstance($config['clientClass'], $config));
    }
}