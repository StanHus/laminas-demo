<?php

declare(strict_types=1);

namespace Application;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;
use Application\Controller\IndexController;
use Application\Model\ProductTable;
use Application\Model\OrderTable;

class Module
{
    /**
     * Load module configuration.
     */
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Define service factories.
     */
    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                // ProductTable service
                ProductTable::class => function ($container) {
                    $tableGateway = $container->get('ProductTableGateway');
                    return new ProductTable($tableGateway);
                },
                'ProductTableGateway' => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new TableGateway('products', $dbAdapter);
                },

                // OrderTable service
                OrderTable::class => function ($container) {
                    $tableGateway = $container->get('OrderTableGateway');
                    return new OrderTable($tableGateway);
                },
                'OrderTableGateway' => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new TableGateway('orders', $dbAdapter);
                },

                // Database adapter (ensure this is available globally)
                AdapterInterface::class => function ($container) {
                    $config = $container->get('config')['db'];
                    return new \Laminas\Db\Adapter\Adapter($config);
                },
            ],
        ];
    }

    /**
     * Define controller factories.
     */
    public function getControllerConfig(): array
    {
        return [
            'factories' => [
                IndexController::class => function ($container) {
                    $productTable = $container->get(ProductTable::class);
                    $orderTable = $container->get(OrderTable::class);

                    return new IndexController($productTable, $orderTable);
                },
            ],
        ];
    }
}
