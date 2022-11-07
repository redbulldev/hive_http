<?php
/**
 * Slim Auth.
 *
 * @link      http://github.com/marcelbonnet/slim-auth
 *
 * @copyright Copyright (c) 2013-2016 Jeremy Kendall (http://about.me/jeremykendall) with changes: (c) 2016 Marcel Bonnet (http://github.com/marcelbonnet)
 * @license   MIT
 */
namespace marcelbonnet\Slim\Auth\Exception;

/**
 * HTTP 401 Unauthorized Exception.
 *
 * I think the name of this class, and of the HTTP response code, is confusing. 
 * It's intended to be used when a user attempts to access a resource when they 
 * are not authenticated and the resource requires authentication. That's 
 * AUTHENTICATION, not AUTHORIZATION, so confusing, but there ya go.
 *
 * @see https://httpstatuses.com/401 401 Unauthorized - httpstatuses.com
 */
final class HttpUnauthorizedException extends \RuntimeException implements HttpException
{
    /**
     * @var int HTTP status code
     */
    private $statusCode;

    /**
     * Public constructor.
     */
    public function __construct()
    {
        $message = 'You must authenticate to access this resource.';
        $code = 401;
        $this->statusCode = $code;

        parent::__construct($message, $code);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
