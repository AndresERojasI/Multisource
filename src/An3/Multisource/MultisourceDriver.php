<?php

namespace An3\Multisource;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

/**
 * This is the class that has the User Provider Logic.
 */
class MultisourceDriver implements UserProvider
{
    /**
     * The Eloquent user model.
     *
     * @var string
     */
    protected $model;

    /**
     * Enabled sources array.
     *
     * @var array
     */
    protected $enabledSources;

    /**
     * Contructor.
     *
     * @param [type] $model [description]
     */
    public function __construct($model, $enabledSources)
    {
        $this->model = $model;
        $this->enabledSources = $enabledSources;
    }

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
     * @param UserContract $user  [description]
     * @param [type]       $token [description]
     *
     * @return [type] [description]
     */
    public function updateRememberToken(UserContract $user, $token)
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
     * @param UserContract $user        [description]
     * @param array        $credentials [description]
     *
     * @return [type] [description]
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
    }

    /**
     * Create a new instance of the model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class();
    }

    /**
     * Gets the name of the Eloquent user model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Sets the name of the Eloquent user model.
     *
     * @param string $model
     *
     * @return $this
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }
}
