<?php
namespace Imi\Rpc;

use Imi\Server\Http\Server;
use Imi\Rpc\Contract\IRpcServer;

/**
 * RPC Http 服务器基类
 */
abstract class BaseRpcHttpServer extends Server implements IRpcServer
{

}
