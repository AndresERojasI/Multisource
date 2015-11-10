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
     * Constructor.
     *
     * @param [type] $model [description]
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
}
