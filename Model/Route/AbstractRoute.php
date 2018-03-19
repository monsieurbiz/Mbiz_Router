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
namespace Mbiz\Router\Model\Route;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Abstract
 * @package Mbiz\Router\Model\Route
 */
abstract class AbstractRoute extends AbstractModel
{
    /**
     * The router used for matching path
     * @var \Zend_Controller_Router_Route_Abstract
     */
    protected $_routerObject = null;

    /**
     * Indicate if it's a matching route
     * @var bool
     */
    protected $_match = false;

    /**
     * Returns the router
     * @return \Zend_Controller_Router_Route_Abstract
     */
    public function getRouterObject()
    {
        return $this->_routerObject;
    }

    /**
     * Set if this route is matching path
     * @param bool $match
     * @return $this
     */
    public function setMatch($match)
    {
        $this->_match = (bool) $match;
        return $this;
    }

    /**
     * Get if this route match the path
     * @return bool
     */
    public function isMatch()
    {
        return $this->_match;
    }

    /**
     * Set/Get attribute wrapper Or Router's methods wrapper
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, $args)
    {
        try {
            $result = parent::__call($method, $args);
            return $result;
        } catch (Varien_Exception $e) {
            $router = $this->getRouterObject();
            return call_user_func_array([$router, $method], $args);
        }
    }
}
