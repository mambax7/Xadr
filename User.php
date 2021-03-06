<?php
/*
 * This file has its roots as part of the Mojavi package which was
 * Copyright (c) 2003 Sean Kerr. It has been incorporated into this
 * derivative work under the terms of the LGPL V2.1.
 * (http://www.gnu.org/licenses/lgpl-2.1.html)
 */

namespace Xmf\Xadr;

/**
 * A User object provides an interface to data representing an individual
 * user, allowing for access and managment of attributes and security
 * related data.
 *
 * @category  Xmf\Xadr\User
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @author    Sean Kerr <skerr@mojavi.org>
 * @copyright 2013-2015 XOOPS Project (http://xoops.org)
 * @copyright 2003 Sean Kerr
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class User extends ContextAware
{

    /**
     * The authenticated status of the user.
     *
     * @var boolean
     */
    protected $authenticated = false;

    /**
     * Clear all user data.
     *
     * @return void
     */
    public function clearAll()
    {
        $this->authenticated = false;
    }

    /**
     * Determine the authenticated status of the user.
     *
     * @return boolean TRUE if the user is authenticated, otherwise FALSE.
     */
    public function isAuthenticated()
    {
        return (boolean) $this->authenticated;
    }

    /**
     * Set the authenticated status of the user.
     *
     * @param boolean $status The authentication status.
     *
     * @return void
     */
    public function setAuthenticated($status)
    {
        $this->authenticated = (boolean) $status;
    }

    /**
     * Determine if the user has a privilege -- always false
     *
     * @param Privilege $privilege a privilege object describing a required privilege
     *
     * @return boolean
     */
    public function hasPrivilege($privilege)
    {
        return false;
    }
}
