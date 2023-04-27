<?php

declare(strict_types=1);

namespace Imi\Rpc\Route\Annotation;

use Imi\Bean\Annotation\Base;
use Imi\Bean\Annotation\Parser;
use Imi\Rpc\Route\Annotation\Contract\IRpcAction;

/**
 * RPC 动作注解.
 *
 * @Annotation
 *
 * @Target("METHOD")
 *
 * @Parser("Imi\Rpc\Route\Annotation\Parser\RpcControllerParser")
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
class RpcAction extends Base implements IRpcAction
{
}
