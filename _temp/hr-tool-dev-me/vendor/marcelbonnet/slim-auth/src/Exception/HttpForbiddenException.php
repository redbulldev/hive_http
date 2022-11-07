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
 * HTTP 403 Forbidden Exception.
 *
 * @see https://httpstatuses.com/403 403 Forbidden - httpstatuses.com
 */
final class HttpForbiddenException extends \RuntimeException implements HttpException
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
        $message = 'You are not authorized to access this resource';
        $code = 403;
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
