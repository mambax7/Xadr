<?php
/*
 * This file has its roots as part of the Mojavi package which was
 * Copyright (c) 2003 Sean Kerr. It has been incorporated into this
 * derivative work under the terms of the LGPL V2.1.
 * (http://www.gnu.org/licenses/lgpl-2.1.html)
 */

namespace Xmf\Xadr\Validator;

/**
 * String provides a constraint on a parameter by making sure
 * the value matches required minimum and maximum lengths and contains
 * only allowable characters
 *
 * @category  Xmf\Xadr\Validator\String
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @author    Sean Kerr <skerr@mojavi.org>
 * @copyright 2013-2015 XOOPS Project (http://xoops.org)
 * @copyright 2003 Sean Kerr
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class String extends AbstractValidator
{

    /**
     * Execute this validator.
     *
     * @param string &$value parameter value - can be changed by reference.
     *
     * @return bool TRUE if the validator completes successfully, otherwise FALSE.
     */
    public function execute (&$value)
    {
        $value = (string) $value;
        if ($this->params['trim']) {
            $value = trim($value);
        }

        $length = mb_strlen($value, 'UTF-8');

        if ($this->params['min'] > -1 && $length < $this->params['min']) {
            $this->setErrorMessage($this->params['min_error']);
            return false;
        }

        if ($this->params['max'] > -1 && $length > $this->params['max']) {
            $this->setErrorMessage($this->params['max_error']);
            return false;
        }


        return $this->checkAllowedCharacters($value);
    }

    /**
     * checkAllowedCharacters - check that string contains only allowed characters.
     *
     * @param string $value value to check
     *
     * @return boolean true if allowed, false if disallowed
     */
    protected function checkAllowedCharacters($value)
    {
        $length = mb_strlen($value, 'UTF-8');
        $array = array_flip($this->params['chars']);
        if (!empty($array)) {
            for ($i = 0; $i < $length; $i++) {
                $tmp = mb_substr($value, $i, 1, 'UTF-8');
                $found = isset($array[$tmp]);
                if (($this->params['allowed'] && !$found)
                    || (!$this->params['allowed'] && $found)
                ) {
                    $this->setErrorMessage($this->params['chars_error']);
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * getDefaultParameters
     *
     * All expected parameters should be listed here and be given a default value
     *
     * Initialization Parameters:
     *
     * Name    | Type  | Default | Required | Description
     * ------- | ----- | ------- | -------- | -----------
     * allowed | bool  | FALSE   | yes      | true array is allowed values,
     *         |       |         |          | false array is disallowed values
     * chars   | array | n/a     | yes      | an indexed array of characters
     * max     | int   | n/a     | no       | a maximum length
     * min     | int   | n/a     | no       | a minimum length
     * trim    | bool  | TRUE    | no       | true to trim value before comparison
     *
     * Error Messages:
     *
     * Name        | Default
     * ----------- | -------
     * chars_error | Value contains an invalid character
     * max_error   | Value is too long
     * min_error   | Value is too short
     *
     * @return array of default parameters
     */
    public function getDefaultParams()
    {
        $defaults = array(
            'allowed'     => false,
            'chars'       => array(),
            'chars_error' => 'Value contains an invalid character',
            'max'         => -1,
            'max_error'   => 'Value is too long',
            'min'         => -1,
            'min_error'   => 'Value is too short',
            'trim'        => true,
        );
        return $defaults;
    }
}
