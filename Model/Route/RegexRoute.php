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

use Mbiz\Router\Controller\Route\Regex;

class RegexRoute extends AbstractRoute implements RouteInterface
{

    /**
     * Configure the router with data used for matching path
     * @return $this
     */
    public function configure()
    {
        if ($this->_routerObject === null) {
            $this->_routerObject = new Regex($this->getRoute(), $this->getDefaults(), $this->getMap(), $this->getReverse());
        }
        return $this;
    }
}
