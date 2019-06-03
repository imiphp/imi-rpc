<?php
namespace Imi\Rpc\Client;

interface IService
{
    /**
     * 调用服务
     *
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function call($method, $args = []);

    /**
     * 获取客户端对象
     *
     * @return IRpcClient
     */
    public function getClient(): IRpcClient;

}