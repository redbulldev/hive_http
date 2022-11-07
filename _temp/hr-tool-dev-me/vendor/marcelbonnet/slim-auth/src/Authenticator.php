<?php
/**
 * Slim Auth.
 *
 * @link      http://github.com/marcelbonnet/slim-auth
 *
 * @copyright Copyright (c) 2013-2016 Jeremy Kendall (http://about.me/jeremykendall) with changes: (c) 2016 Marcel Bonnet (http://github.com/marcelbonnet)
 * @license   MIT
 */
namespace marcelbonnet\Slim\Auth;

use Zend\Authentication\AuthenticationServiceInterface;

/**
 * Authenticates users.
 */
final class Authenticator
{
    /**
     * @var AuthenticationServiceInterface ZF Authentication Service
     */
    private $auth;

    /**
     * Public constructor.
     *
     * @param AuthenticationServiceInterface $auth
     */
    public function __construct(AuthenticationServiceInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Authenticates user.
     *
     * @param string $identity   User identifier (username, email, etc)
     * @param string $credential User password
     *
     * @return Zend\Authentication\Result
     *
     * @throws Zend\Authentication\Exception\RuntimeException
     */
    public function authenticate($identity, $credential)
    {
        $adapter = $this->auth->getAdapter();
        $adapter->setIdentity($identity);
        $adapter->setCredential($credential);
        return $this->auth->authenticate();
    }

    /**
     * Clears the identity from persistent storage.
     */
    public function logout()
    {
        $this->auth->clearIdentity();
    }

    /**
     * Returns true if and only if an identity is available.
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->auth->hasIdentity();
    }

    /**
     * Returns the authenticated identity or null if no identity is available.
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->auth->getIdentity();
    }
}
