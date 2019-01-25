<?php
namespace Imi\Rpc\Route;

use Imi\ServerManage;
use Imi\Bean\Annotation\Bean;
use Imi\Util\ObjectArrayHelper;
use Imi\Server\Route\RouteCallable;
use Imi\Rpc\Controller\RpcController;
use Imi\Rpc\Route\Annotation\RpcRoute as RpcRouteAnnotation;
use Imi\Util\Text;

/**
 * @Bean("RpcRoute")
 */
class RpcRoute implements IRoute
{
    /**
     * 路由解析处理
     * @param mixed $data
     * @return array
     */
    public function parse($data)
    {
        return null;
    }

    /**
     * 增加路由规则，直接使用注解方式
     * @param Imi\Rpc\Route\Annotation\RpcRoute $annotation
     * @param mixed $callable
     * @param array $options
     * @return void
     */
    public function addRuleAnnotation(RpcRouteAnnotation $annotation, $callable, $options = [])
    {
        $serverName = $options['serverName'];
        $controllerAnnotation = $options['controller'];
        $methodName = $options['methodName'];
        $rpcServer = ServerManage::getServer($serverName)->getRpcService();

        // callable
        $callable = $this->parseCallable($callable);
        $isObject = is_array($callable) && isset($callable[0]) && $callable[0] instanceof RpcController;
        if($isObject)
        {
            // 复制一份控制器对象
            $callable[0] = clone $callable[0];
        }

        $eventName = 'IMI.RPC.ROUTE.ADD_RULE:' . $annotation->rcpType;
        Event::trigger($eventName, func_get_args(), $this);

    }

    /**
     * 处理回调
     * @param array $params
     * @param mixed $callable
     * @return callable
     */
    private function parseCallable($callable)
    {
        if($callable instanceof RouteCallable)
        {
            return $callable->getCallable();
        }
        else
        {
            return $callable;
        }
    }
}