<?php

declare(strict_types=1);

namespace Application\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;

class ProductTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getProduct($id)
    {
        $rowset = $this->tableGateway->select(['id' => (int) $id]);
        return $rowset->current();
    }

    public function updatePaymentStatus($id, $status)
    {
        $this->tableGateway->update(
            ['payment_status' => $status], // Update the status
            ['id' => (int) $id]            // Where condition
        );
    }
}
