<?php
namespace Imi\Rpc\Client\Pool;

use Imi\App;
use Imi\Bean\BeanFactory;
use Imi\Pool\BaseAsyncPool;
use Imi\Pool\TUriResourceConfig;

/**
 * Swoole协程RPC连接池
 */
class RpcClientCoroutinePool extends BaseAsyncPool
{
    use TUriResourceConfig;

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
        return new RpcClientResource($this, BeanFactory::newInstance($config['clientClass'], $config));
    }
}