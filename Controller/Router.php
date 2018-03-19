<?php
/**
 * This file is part of Mbiz_Router for Magento.
 *
 * @license MIT
 * @author Maxime Huran <m.huran@monsieurbiz.com> <@MaximeHuran>
 * @category Mbiz
 * @package Mbiz_Router
 * @copyright Copyright (c) 2018 Monsieur Biz (https://monsieurbiz.com/)
 */

namespace Mbiz\Router\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Event\Manager as EventManager;
use Mbiz\Router\Model\Route\StaticRouteFactory;
use Mbiz\Router\Model\Route\RegexRouteFactory;

class Router implements RouterInterface
{
    /**
     * Configuration tag who contains custom routes
     * @const ROUTER_NAME string
     */
    const ROUTER_NAME = 'mbiz_router';

    /**
     * Route types matching Zend_Controller_Router_Route_Static
     * @const ROUTE_STYPE_STATIC string
     */
    const ROUTE_TYPE_STATIC = 'static';

    /**
     * Route types matching Zend_Controller_Router_Route_Regex
     * @const ROUTE_STYPE_REGEX string
     */
    const ROUTE_TYPE_REGEX = 'regex';

    /**
     * Route type "Custom" for custom controller router.
     * @const ROUTE_TYPE_CUSTOM string
     */
    const ROUTE_TYPE_CUSTOM = 'custom';

    /**
     * The registry key for keep the router
     * @const REG_KEY string
     */
    const REG_KEY = 'mbiz_router_controller_router';

    /**
     * All custom routers
     * @var array
     * @access protected
     */
    protected $_routers = null;

    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var StaticRouteFactory
     */
    protected $staticRouteFactory;

    /**
     * @var RegexRouteFactory
     */
    protected $regexRouteFactory;

    /**
     * @var bool
     */
    protected $dispatched = false;

    /**
     * Router constructor.
     * @param ActionFactory $actionFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param EventManager $eventManager
     * @param StaticRouteFactory $staticRouteFactory
     * @param RegexRouteFactory $regexRouteFactory
     */
    public function __construct(
        ActionFactory $actionFactory,
        ScopeConfigInterface $scopeConfig,
        EventManager $eventManager,
        StaticRouteFactory $staticRouteFactory,
        RegexRouteFactory $regexRouteFactory
    ) {
        $this->actionFactory = $actionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->eventManager = $eventManager;
        $this->staticRouteFactory = $staticRouteFactory;
        $this->regexRouteFactory = $regexRouteFactory;
    }

    /**
     * Check the current URL for any matching with all custom routes.
     * @param RequestInterface $request
     * @return mixed
     */
    public function match(RequestInterface $request)
    {
        // Do nothing if request is already dispatched
        if ($request->isDispatched() || $this->dispatched) {
            return null;
        }

        // Dispatch event before matching routes
        $this->eventManager->dispatch(self::ROUTER_NAME . '_before', [
            'request' => $request,
            'controller_router' => $this
        ]);

        // Nothing to do if no router
        $routers = $this->getAllRouters();
        if (empty($routers)) {
            return null;
        }

        // Try match with all routers
        $path = trim($request->getPathInfo(), '/');
        foreach ($routers as $routerName => $router) {
            if (false !== $params = $router->getRouterObject()->match($path)) {
                break;
            }
        }

        // No router matched
        if ($params === false) {
            return null;
        }

        // Set params to be able to modify it in observer
        $router->setParams($params);

        // Dispatch event indicating that route match
        $this->eventManager->dispatch(self::ROUTER_NAME . '_match_' . $routerName, [
                'request' => $request,
                'controller_router' => $this,
                'router' => $router
        ]);

        // Set module, controller, action and params for redirect
        $request->setModuleName($router->getModule())
            ->setControllerName($router->getController())
            ->setActionName($router->getAction())
            ->setParams($router->getParams())
        ;

        // Dispatch event after matching routes
        $this->eventManager->dispatch(self::ROUTER_NAME . '_after', [
            'request' => $request,
            'controller_router' => $this
        ]);

        $this->dispatched = true;
        return $this->actionFactory->create('Magento\Framework\App\Action\Forward');
    }

    /**
     * Get all the routes in configuration files.
     * @return array FALSE if no routes.
     */
    public function getRoutes()
    {
        return $this->scopeConfig->getValue(self::ROUTER_NAME) ?? false;
    }


    /**
     * Get all routers objects.
     * All routers are Zend_Controller_Router_Route_* or custom objects.
     * @return array
     */
    public function getAllRouters()
    {
        // Already initialized
        if ($this->_routers !== null) {
            return $this->_routers;
        }

        $this->_routers = [];

        // Return empty array if no routes
        $routes = $this->getRoutes();
        if (!$routes) {
            return $this->_routers;
        }

        foreach ($routes as $routeName => $route) {;

            if ($routeName == 'routers') continue;

            // Check type
            if (!isset($route['type'])) {
                throw new Exception('No type defined for the "' . $routeName . '" route.');
            }

            // Check module
            if (!isset($route['module'])) {
                throw new Exception('No module defined for the "' . $routeName . '" route.');
            }

            // Check controller
            if (!isset($route['controller'])) {
                $route['controller'] = 'index';
            }

            // Check action
            if (!isset($route['action'])) {
                $route['action'] = 'index';
            }

            $route['route_name'] = $routeName;

            // Append new router
            switch ($route['type']) {
//                case self::ROUTE_TYPE_CUSTOM:
//                    if (!isset($route['class'])) {
//                        throw new Exception('No class defined for the "' . $routeName . '" route.');
//                    }
//                    $this->_routers[$routeName] = $model->setData($route)->configure(); // @TODO : get model with object manager
//                    break;
                case self::ROUTE_TYPE_STATIC:
                    $this->_routers[$routeName] = $this->staticRouteFactory->create()->setData($route)->configure();
                    break;
                case self::ROUTE_TYPE_REGEX:
                    $this->_routers[$routeName] = $this->regexRouteFactory->create()->setData($route)->configure();
                    break;
                default:
                    throw new Exception('Incorrect type defined for the "' . $routeName . '" route.');
                    break;
            }
        }


        return $this->_routers;
    }

}
