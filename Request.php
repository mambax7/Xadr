<?php
/*
 * This file has its roots as part of the Mojavi package which was
 * Copyright (c) 2003 Sean Kerr. It has been incorporated into this
 * derivative work under the terms of the LGPL V2.1.
 * (http://www.gnu.org/licenses/lgpl-2.1.html)
 */

namespace Xmf\Xadr;

/**
 * A Request object holds data related to an application request including
 * the parameters (user input,) as well as related attributes and messages
 * established by the action(s) invoked as the request is proccessed.
 *
 * @category  Xmf\Xadr\Request
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @author    Sean Kerr <skerr@mojavi.org>
 * @copyright 2013-2015 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @copyright 2003 Sean Kerr
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Request
{

    /**
     * An attributes object.
     *
     * @var XadrArray
     */
    protected $attributes;

    /**
     * An associative array of errors.
     *
     * @var XadrArray
     */
    protected $errors;

    /**
     * The request method (REQUEST_GET, REQUEST_GET) used for this request.
     *
     * @var int
     */
    protected $method = 0;

    /**
     * An associative array of user submitted parameters.
     *
     * @var XadrArray
     */
    protected $parameters;

    /**
     * Create a new Request instance. The three main pieces of request data - parameters,
     * attributes and errors - can be injected or will be created if omitted.
     *
     * Parameters are the input data, typically user input, such as web form or query data.
     *
     * XadrArray are application generated in response to processing the input parameters.
     * Internally this is used to pass data between action, domain and responder. One typical
     * use is to capture raw data in an action that will be used by the responder, possibly
     * as template variables for rendering.
     *
     * Errors are message generated during validation of parameters, and will be stored under
     * the same key as the parameter that triggered the validation error.
     *
     * @param XadrArray|array|null $parameters A parsed array of user submitted parameters.
     * @param XadrArray|array|null $attributes Array of application generated attributes
     * @param XadrArray|array|null $errors     A array of errors generated by the application
     */
    public function __construct($parameters = null, $attributes = null, $errors = null)
    {
        $this->parameters = $this->getXadrArrayObject(($parameters === null)
                                ? $this->fetchSystemParameters() : $parameters);
        $this->attributes = $this->getXadrArrayObject($attributes);
        $this->errors     = $this->getXadrArrayObject($errors);
    }

    /**
     * convert input into a Attribute object
     *
     * @param XadrArray|array|null $input supplied input value
     *
     * @return XadrArray an object, either as passed, converted or new
     */
    private function getXadrArrayObject($input)
    {
        if ($input instanceof XadrArray) {
            return $input;
        }
        if (is_array($input)) {
            return new XadrArray($input);
        }
        return new XadrArray();
    }

    /**
     * access the attributes object for the request
     *
     * @return XadrArray
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * Retrieve the request method used for this request.
     *
     * @return integer A request method that is one of the following:
     * - Xadr::REQUEST_GET  - serve GET requests
     * - Xadr::REQUEST_POST - serve POST requests
     */
    public function getMethod()
    {
        if ($this->method == 0) {
            $requestMethod = strtoupper(\Xmf\Request::getMethod());
            $this->method |= ($requestMethod === 'POST') ? Xadr::REQUEST_POST : 0;
            $this->method |= ($requestMethod === 'GET') ? Xadr::REQUEST_GET : 0;
        }
        return $this->method;
    }

    /**
     * Set the request method.
     *
     * @param int $method A request method that is one of the following:
     * - Xadr::REQUEST_GET  - serve GET requests
     * - Xadr::REQUEST_POST - serve POST requests
     *
     * @return void
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * access the parameters object for the request
     *
     * @return XadrArray
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * Retrieve a user submitted parameter.
     *
     * @param string $name  A parameter name.
     * @param mixed  $value A default value.
     *
     * @return mixed A parameter value, if the given parameter exists,
     *               otherwise NULL.
     */
    public function getParameter($name, $value = null)
    {
        return $this->parameters->get($name, $value);
    }

    /**
     * Determine if the request has a parameter.
     *
     * @param string $name A parameter name.
     *
     * @return bool TRUE if the given parameter exists, otherwise FALSE.
     */
    public function hasParameter($name)
    {
        return $this->parameters->has($name);
    }

    /**
     * access the errors object for the request
     *
     * @return XadrArray
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Retrieve an error message.
     *
     * @param string $name The name under which the message has been
     *                     registered. If the error is validation related,
     *                     it will be registered under a parameter name.
     *
     * @return string An error message if a validation error occured for
     *                      a parameter or was manually set, otherwise NULL.
     */
    public function getError($name)
    {
        return (isset($this->errors[$name])) ? $this->errors[$name] : null;
    }

    /**
     * Retrieve an associative array of errors.
     *
     * @param string|null $nameLike restrict result to only errors with a name starting with
     *                              this string.
     *
     * @return XadrArray|array The errors object (XadrArray) or an array of errors with keys
     *                          matching the specified $nameLike
     */
    public function getErrors($nameLike = null)
    {
        return $this->errors->getAllLike($nameLike);
    }

    /**
     * Retrieve errors as an HTML string
     *
     * @param string|null $nameLike restrict output to only errors with a name starting with
     *                              this string. This can be exploited by the application by
     *                              specify unique prefixes (such as 'global:') for errors not
     *                              related to a specific parameter.
     *
     * @return string HTML representation of errors
     */
    public function getErrorsAsHtml($nameLike = null)
    {
        $errorsoutput = '';
        if ($this->hasErrors()) {
            $errors = $this->errors->getAllLike($nameLike);
            if (!empty($errors)) {
                $errorsoutput = \Xoops::getInstance()->alert('error', $errors, 'Error');
            }
        }
        return $errorsoutput;
    }

    /**
     * Determine if an error has been set.
     *
     * @param string $name The name under which the message has been registered.
     *                      If the error is validation related, it will be
     *                      registered under a parameter name.
     *
     * @return bool TRUE if an error is set for the key, otherwise FALSE.
     */
    public function hasError($name)
    {
        return isset($this->errors[$name]);
    }

    /**
     * Determine if any error has been set.
     *
     * @return bool TRUE if any error has been set, otherwise FALSE.
     */
    public function hasErrors()
    {
        return (count($this->errors) > 0);
    }

    /**
     * Set an error message.
     *
     * @param string $name    The name under which to register the message.
     * @param string $message An error message.
     *
     * @return void
     */
    public function setError($name, $message)
    {
        $this->errors->set($name, $message);
    }

    /**
     * Set multiple error messages.
     *
     * @param array $errors An associative array of error messages.
     *
     * @return void
     */
    public function setErrors($errors)
    {
        $this->errors->setMerge($errors);
    }

    /**
     * get parameters as appropriate to the request methog
     *
     * @return array An associative array of parameters.
     */
    private function fetchSystemParameters()
    {
        // start with query parameters, as they can always be set
        $values = (array) $this->getHttpQueryParameters();
        // merge body parameters only if this is a post
        if ($this->getMethod() === Xadr::REQUEST_POST) {
            $values = array_merge($values, (array) $this->getHttpBodyParameters());
        }
        return $values;
    }

    /**
     * Attribute object of query parameters
     *
     * @var XadrArray
     */
    protected $httpQueryParameters = null;

    /**
     * Attribute object of body parameters
     *
     * @var XadrArray
     */
    protected $httpBodyParameters = null;

    /**
     * get parameters from the http query (i.e. $_GET)
     *
     * @return XadrArray An XadrArray object of query parameters.
     */
    public function getHttpQueryParameters()
    {
        if ($this->httpQueryParameters === null) {
            $this->httpQueryParameters = $this->getXadrArrayObject(\Xmf\Request::get('GET', 0));
        }
        return $this->httpQueryParameters;
    }

    /**
     * get parameters from the http body (i.e. $_POST)
     *
     * @return XadrArray An XadrArray object of body parameters.
     */
    public function getHttpBodyParameters()
    {
        if ($this->httpBodyParameters === null) {
            $this->httpBodyParameters = $this->getXadrArrayObject(\Xmf\Request::get('POST', 0));
        }
        return $this->httpBodyParameters;
    }
}
