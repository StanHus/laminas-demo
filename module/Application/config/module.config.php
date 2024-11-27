<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'payment' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application/checkout[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+', // Product ID must be numeric
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'checkout',
                    ],
                ],
            ],
            'payment-success' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/payment/success',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'success',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => function ($container) {
                $productTable = $container->get(Model\ProductTable::class);
                $orderTable = $container->get(Model\OrderTable::class);

                return new Controller\IndexController($productTable, $orderTable);
            },
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'application/index/checkout' => __DIR__ . '/../view/application/index/checkout.phtml',
            'application/index/success' => __DIR__ . '/../view/application/index/success.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
