<?php

namespace An3\Multisource\Sources;

/**
 * This interface defines the base methods that every
 * source Class should at least implement.
 */
interface BaseSource
{
    public function findById($identifier);
    public function findByCredentials($user, array $credentials);
    public function updateRememberToken($user, $token);
    public function validateCredentials($user, array $credentials);
}
