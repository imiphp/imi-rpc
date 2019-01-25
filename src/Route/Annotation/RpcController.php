<?php
namespace Imi\Rpc\Route\Annotation;

use Imi\Bean\Annotation\Base;
use Imi\Bean\Annotation\Parser;

/**
 * RPC 控制器注解
 * @Annotation
 * @Target("CLASS")
 * @Parser("Imi\Rpc\Route\Annotation\Parser\RpcControllerParser")
 */
class RpcController extends Base
{
    /**
     * 只传一个参数时的参数名
     * @var string
     */
    protected $defaultFieldName = 'prefix';

    /**
     * 路由前缀
     * @var string
     */
    public $prefix;
}