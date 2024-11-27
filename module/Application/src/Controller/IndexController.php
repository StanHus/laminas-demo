<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class IndexController extends AbstractActionController
{
    private $productTable;
    private $orderTable;

    public function __construct($productTable, $orderTable)
    {
        $this->productTable = $productTable;
        $this->orderTable = $orderTable;
    }

    public function indexAction()
    {
        $allProducts = $this->productTable->fetchAll();
        $products = [
            'purchased' => [],
            'available' => [],
        ];

        foreach ($allProducts as $product) {
            if ($product->payment_status === 'success') {
                $products['purchased'][] = $product;
            } else {
                $products['available'][] = $product;
            }
        }

        return new ViewModel(['products' => $products]);
    }

    public function checkoutAction()
    {
        $productId = $this->params()->fromQuery('id');
        $product = $this->productTable->getProduct($productId);

        if (!$product) {
            return $this->redirect()->toRoute('home');
        }

        // Stripe::setApiKey( getenv('STRIPE_SECRET_KEY'));
        Stripe::setApiKey( 'sk_test_51QPoCDCl3Va0ZNV6vg2QOL4XCupHlwX1h2u1IfaP1gVza4TFwSQm2laeDdOuuHUkjQqkO9Bq4rRr6nXEJ4G9LqKC00kRAGZfHE');

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => (int) ($product->price * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->url()->fromRoute(
                'payment-success',
                [],
                ['force_canonical' => true]
            ) . '?id=' . $productId, // Append the product ID to the success URL
            'cancel_url' => $this->url()->fromRoute(
                'home',
                [],
                ['force_canonical' => true]
            ),
        ]);

        return $this->redirect()->toUrl($session->url);
    }

    public function successAction()
    {
        $productId = $this->params()->fromQuery('id'); // Ensure 'id' is passed as a query parameter

        if ($productId) {
            // Update payment status in the database
            $this->productTable->updatePaymentStatus($productId, 'success');
        }

        return new ViewModel([
            'message' => 'Your purchase was successful!',
        ]);
    }
    }
