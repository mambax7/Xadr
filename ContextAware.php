<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Xadr;

/**
 * ContextAware makes shared context available
 *
 * Shared context makes the following available:
 * Controller() -
 *
 * @category  Xmf\Xadr\ContextAware
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
abstract class ContextAware
{

    protected $context = null;

    /**
     * ContextAware __construct
     *
     * @param Controller $context - context object
     */
    public function __construct(Controller $context)
    {
        $this->context = $context;
    }

    /**
     * Instance of the Config object
     *
     * @return object shared configuration
     *
     * @since      1.0
     */
    public function Config()
    {
        return $this->context->getConfig();
    }

    /**
     * Instance of the full context. At present this is the controller
     *
     * @return object shared context
     *
     * @since      1.0
     */
    public function Context()
    {
        return $this->context;
    }

    /**
     * Get the controller context
     *
     * @return object Xmf\Xadr\Controller instance
     * @since      1.0
     */
    public function Controller()
    {
        return $this->context;
    }

    /**
     * Get the request context
     *
     * @return object Xmf\Xadr\Request instance
     * @since      1.0
     */
    public function Request()
    {
        return $this->context->getRequest();
    }

    /**
     * Get the user context
     *
     * @return object Xmf\Xadr\User instance
     * @since      1.0
     */
    public function User()
    {
        return $this->context-> getUser();
    }

    /**
     * Get the DomainManager instance
     *
     * @return object Xmf\Xadr\DomainManager instance
     * @since      1.0
     */
    public function Domain()
    {
        return $this->context->getDomain();
    }
}
