<?php
namespace Imi\Rpc\Client;

interface IRpcClient
{
    /**
     * 构造方法
     *
     * @param array $config 配置
     */
    public function __construct($config);

    /**
     * 打开
     * @return boolean
     */
    public function open();

    /**
     * 关闭
     * @return void
     */
    public function close();

    /**
     * 是否已连接
     * @return bool
     */
    public function isConnected(): bool;

    /**
     * 获取实例对象
     *
     * @return mixed
     */
    public function getInstance();

    /**
     * 获取服务对象
     *
     * @param string $name 服务名
     * @return IService
     */
    public function getService($name = null): IService;

}