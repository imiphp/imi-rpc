<?php
namespace Imi\Rpc\Route;

interface IRoute
{
    /**
     * 路由解析处理
     * @param mixed $data
     * @return array
     */
    public function parse($data);
}