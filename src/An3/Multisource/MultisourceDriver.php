<?php

namespace An3\Multisource;

use Illuminate\Auth\EloquentUserProvider;

/**
 * This is the class that has the User Provider Logic.
 */
class MultisourceDriver extends EloquentUserProvider
{
    /**
     * [retrieveById description].
     *
     * @param [type] $identifier [description]
     *
     * @return [type] [description]
     */
    public function retrieveById($identifier)
    {
    }

    /**
     * [retrieveByToken description].
     *
     * @param [type] $identifier [description]
     * @param [type] $token      [description]
     *
     * @return [type] [description]
     */
    public function retrieveByToken($identifier, $token)
    {
    }

    /**
     * [updateRememberToken description].
     *
     * @param Authenticatable $user  [description]
     * @param [type]          $token [description]
     *
     * @return [type] [description]
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    /**
     * [retrieveByCredentials description].
     *
     * @param array $credentials [description]
     *
     * @return [type] [description]
     */
    public function retrieveByCredentials(array $credentials)
    {
    }

    /**
     * [validateCredentials description].
     *
     * @param Authenticatable $user        [description]
     * @param array           $credentials [description]
     *
     * @return [type] [description]
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
    }
}
