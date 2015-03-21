<?php
namespace Xmf\Xadr\Exceptions;

/**
 * InvalidCatalogEntryException - It is invalid to invoke the catalog() method on an Entry
 * object that has not been added to a catalog.
 *
 * @category  Xmf\Xadr\Exceptions
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class InvalidCatalogException extends \LogicException
{
}
