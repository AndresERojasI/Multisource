<?php

namespace An3\Multisource\Sources;

/**
 * LDAP based login source.
 */
class LDAPSource implements BaseSource
{
    /**
     * Adldap\Adldap instance.
     *
     * @var Adldap\Adldap
     */
    private $ldap;

    /**
     * Constructor.
     */
    public function __construct($ldap)
    {
        $this->ldap = $ldap;
    }

    /**
     * Looks up a user by its ID.
     *
     * @param string $identifier user search id
     *
     * @return Authenticatable Authenticable interface
     */
    public function findById($identifier)
    {
    }

    /**
     * [retrieveByCredentials description].
     *
     * @param array $credentials [description]
     *
     * @return [type] [description]
     */
    public function findByCredentials($user, array $credentials)
    {
        die('here');
    }

    /**
     * [validateCredentials description].
     *
     * @param [type] $user        [description]
     * @param array  $credentials [description]
     *
     * @return [type] [description]
     */
    public function validateCredentials($user, array $credentials)
    {
    }

    /**
     * [updateRememberToken description].
     *
     * @param [type] $user        [description]
     * @param array  $credentials [description]
     *
     * @return [type] [description]
     */
    public function updateRememberToken($user, $token)
    {
    }
}
