<?php
namespace Imi\Rpc\Route\Annotation;

use Imi\Bean\Annotation\Base;
use Imi\Bean\Annotation\Parser;

/**
 * RPC 动作注解
 * @Annotation
 * @Target("METHOD")
 * @Parser("Imi\Rpc\Route\Annotation\Parser\RpcControllerParser")
 */
class RpcAction extends Base
{
    
}