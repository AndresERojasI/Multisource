<?php

namespace An3\Multisource;

/**
 * This is a factory class that builds and enables all of the available
 * configured sources. This class is designed in case we need more logic
 * to each initialization.
 */
class SourceFactory
{
    /**
     * This function initializes the factory, reads the component config file
     * and builds every source the user confirmed.
     *
     * @return [type] [description]
     */
    public static function buildSources($model)
    {
        //this list should be updated if a new source is added
        $availableSources = ['ldap', 'couchdb'];

        //Now we load the selected sources from the config file
        $config = require_once __DIR__.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.'multisource.php';
        //Now we load the user configuration
        $userConfig = \Config::get('multisource');

        if ($userConfig) {
            $config = array_replace_recursive($config, $userConfig);
        }

        $enabledSources = [];

        //Loop through the config to enable all of the Sources
        foreach ($availableSources as $item) {
            if ($config[$item] && $config[$item]['enabled']) {
                $sourceName = $item.'Source';
                $enabledSources[$item] = self::$sourceName($config[$item]);
            }
        }

        return $enabledSources;
    }

    public static function ldapSource($config)
    {
        //let's call the LDAP Library
        $ldap = new \Adldap\Adldap($config);

        //now we inject the Adldap LDAP instance into the source and return it
        return new Sources\LDAPSource($ldap);
    }

    public static function couchDBSource($config)
    {
        //Obtain the Auth Model from the Auth configuration file
        $modelName = '\\'.ltrim(\Config::get('auth.model'), '\\');

        //Remove the enabled part, that's just for another reason.
        if (isset($config['enabled'])) {
            unset($config['enabled']);
        }

        //Return the new object injecting the model
        return new Sources\CouchDBSource(new $modelName());
    }
}
