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

interface RouteInterface
{
    /**
     * Configure the router with data used for matching path
     * @return \Mbiz\Router\Model\Route\AbstractRoute
     */
    public function configure();

    /**
     * Returns the router
     * @return \Zend_Controller_Router_Route_Abstract
     */
    public function getRouterObject();

    /**
     * Set if this route is matching path
     * @param bool $match
     * @return \Mbiz\Router\Model\Route\AbstractRoute
     */
    public function setMatch($match);

    /**
     * Get if this route match the path
     * @return bool
     */
    public function isMatch();
}
