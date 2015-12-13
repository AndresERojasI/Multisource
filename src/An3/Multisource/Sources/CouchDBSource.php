<?php

namespace An3\Multisource\Sources;

/**
 * CouchDB based login source.
 */
class CouchDBSource implements BaseSource
{
    /**
     * Futonquent Model.
     *
     * @var Futonquent user model
     */
    private $model;

    /**
     * Configuration Array.
     *
     * @var array
     */
    private $config;

    /**
     * Whether if create or not a new connection.
     *
     * @var bool
     */
    private $use_local_connection = false;

    /**
     * [$dm description].
     *
     * @var [type]
     */
    private $dm;

    /**
     * [$userModel description].
     *
     * @var [type]
     */
    private $userModel;

    /**
     * Constructor.
     *
     * @param Futonquent\user $model  Use model to be used
     * @param array           $config Configuration Array
     */
    public function __construct($model, $config)
    {
        $this->model = $model;
        $this->config = $config;
        $this->userModel = $config['repository_name'];
        //determine if it needs a new connection or to use the local one.
        $this->use_local_connection = $this->config['use_local_connection'];
        if ($this->use_local_connection) {
            $this->createConnection($this->config['couchdb_config']);
        } else {
            $this->dm = $this->model->getDocumentManager();
        }
    }

    /**
     * Create a couchdb connection.
     *
     * @param array $connectionData Connection information
     *
     * @return [type] [description]
     */
    public function createConnection($connectionData)
    {
        //now we change the default values for the ones the user configured
        $default_config = [
            'database' => '',
            'host' => 'localhost',
            'port' => 5984,
            'username' => null,
            'password' => null,
            'ip' => null ,
            'ssl' => false,
            'models_dir' => app_path(),
            'lucene_handler_name' => '_fti',
            'proxies_dir' => app_path().'storage'.DIRECTORY_SEPARATOR.'proxies',
            'keep-alive' => true,
            'timeout' => '0.01',
            'views_folder' => '../app/couchdb',
            'viewsname' => 'couchsource',
        ];

        $config = array_replace_recursive($default_config, $connectionData);

        $databaseName = $config['database'];
        $documentPaths = array($config['models_dir']);
        $httpClient = new \Doctrine\CouchDB\HTTP\SocketClient($config['host'], $config['port'], $config['username'], $config['password'], $config['ip'], $config['ssl']);
        $httpClient->setOption('keep-alive', $config['keep-alive']);

        $configManager = new \Doctrine\ODM\CouchDB\Configuration();
        $this->metadataDriver = new AnnotationDriver(new AnnotationReader(), $documentPaths);

        // registering noop annotation autoloader - allow all annotations by default
        AnnotationRegistry::registerLoader('class_exists');

        $configManager->setProxyDir($config['proxies_dir']);
        $configManager->setMetadataDriverImpl($this->metadataDriver);
        $configManager->setLuceneHandlerName($config['lucene_handler_name']);

        $connection = new \Doctrine\CouchDB\CouchDBClient($httpClient, $databaseName);
        $view = new FolderDesignDocument($config['views_folder']);
        $connection->createDesignDocument($config['viewsname'], $view);

        $this->dm = new \Doctrine\ODM\CouchDB\DocumentManager($connection, $configManager);
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
        return $this->dm->getRepository($userModel)->find($identifier);
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
        if ($this->use_local_connection) {
            $query = $this->dm->createQuery($this->config['viewsname'], 'remoteCredentials');
        } else {
            $query = $this->dm->createQuery($this->config['viewsname'], 'remoteCredentials');
        }

        $result = $query->setKey($credentials[$this->config['username_field']])
          ->onlyDocs(false)
          ->setLimit(1)
          ->execute();

        $userPassword = ($this->config['encrypt_password']) ?
            \Crypt::decrypt($result[0]['value'][$this->config['remote_password_field']]) :
            $result[0]['value'][$this->config['remote_password_field']];

        if ($userPassword === $credentials[$this->config['password_field']]) {
            return $result[0];
        }

        return;
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
        die('here1');
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
