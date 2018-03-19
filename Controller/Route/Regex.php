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
namespace Mbiz\Router\Controller\Route;

/**
 * Regex Route.
 */
class Regex extends \Zend_Controller_Router_Route_Regex
{
    /**
     * Set the reverse
     * @param string $reverse
     * @return $this
     */
    public function setReverse($reverse)
    {
        $this->_reverse = $reverse;
        return $this;
    }

}
