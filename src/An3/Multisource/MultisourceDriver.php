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
     * configuration array.
     *
     * @var array
     */
    protected $configuration;

    /**
     * Contructor. Initializes variables and loads the enabled sources
     * using the source factory.
     */
    public function __construct($configuration)
    {
        //Load the configuration
        $this->configuration = $configuration;

        //first we create the model
        $this->model = $this->createModel(\Config::get('auth.model'));

        //Now we process the configuration and initialize the correct sources
        $this->enabledSources = SourceFactory::buildSources($this->model);
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param mixed $identifier
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        //first we find the user in the database
        $user = $this->model->find($identifier);

        //now we find the user auth type
        if (!is_null($user) && isset($this->enabledSources[$user->auth_type])) {
            if ($this->enabledSources[$user->auth_type]->findById($identifier) !== null) {
                return $user;
            } else {
                return;
            }
        } else {
            return;
        }
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param mixed  $identifier
     * @param string $token
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        //first we find the user in the database
        $user = $this->model->find($identifier);

        //let's validate the token
        if (!is_null($user) && $user->getRememberToken() === $token) {
            //now we find the user auth type
            if (isset($this->enabledSources[$user->auth_type])) {
                if ($this->enabledSources[$user->auth_type]->findByCredentials($user) !== null) {
                    return $user;
                } else {
                    return;
                }
            }
        }

        return;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string                                     $token
     */
    public function updateRememberToken(UserContract $user, $token)
    {
        $rememberTokenField = $user->getRememberTokenName();
        $attributes[$rememberTokenField] = $token;

        return $user->update($attributes);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param array $credentials
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        //first we find the user in the database
        if (!isset($this->configuration['username_field'])) {
            return;
        }

        $user = $this->model->where(
            [$this->configuration['username_field'] => $credentials[$this->configuration['username_field']]]
        )->first();

        //let's validate the token
        if (!is_null($user) &&
            $credentials[$this->configuration['password_field']] === \Crypt::decrypt($user->{$this->configuration['password_field']})) {
            //now we find the user auth type
            if (isset($this->enabledSources[$user->auth_type])) {
                if ($this->enabledSources[$user->auth_type]->findByCredentials($user, $credentials) !== null) {
                    return $user;
                } else {
                    return;
                }
            }
        }

        return;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array                                      $credentials
     *
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        //first we find the user in the database
        if (!isset($this->configuration['username_field'])) {
            return;
        }

        //let's validate the token
        if (!is_null($user) &&
            $credentials[$this->configuration['password_field']] === \Crypt::decrypt($user->{$this->configuration['password_field']})) {
            //now we find the user auth type
            if (isset($this->enabledSources[$user->auth_type])) {
                if ($this->enabledSources[$user->auth_type]->findByCredentials($user, $credentials) !== null) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        return false;
    }

    /**
     * Create a new instance of the model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel($modelName)
    {
        $class = '\\'.ltrim($modelName, '\\');

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
