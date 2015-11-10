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
}
