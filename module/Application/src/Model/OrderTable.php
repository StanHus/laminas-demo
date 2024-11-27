<?php

namespace Application\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;

class OrderTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function saveOrder($data)
    {
        $this->tableGateway->insert($data);
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }
}
