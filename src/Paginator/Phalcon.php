<?php

namespace Paginator;

class Phalcon implements \Phalcon\Paginator\AdapterInterface
{

    private $data = array();

    /**
     * Adapter constructor
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $phql = $config['phql'];
        $bind = $config['bind'];

        $page = $config['page'];
        $limit = $config['limit'];

        $phql .= " LIMIT {$limit} OFFSET " . (($page - 1) * $limit);

        $query = new \Phalcon\Mvc\Model\Query($phql);
        $query->setDI($config['di']);

        $this->data = $query->execute($bind);
    }

    /**
     * Set the current page number
     *
     * @param int $page
     */
    public function setCurrentPage($page)
    {

    }

    /**
     * Returns a slice of the resultset to show in the pagination
     *
     * @return stdClass
     */
    public function getPaginate()
    {
        return $this->data;
    }

}
