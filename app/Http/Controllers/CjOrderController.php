<?php

namespace App\Http\Controllers;

use App\Services\CJ\CjOrderService;
use Illuminate\Http\Request;

class CjOrderController extends Controller
{
      protected $cjOrderService;

    public function __construct(CjOrderService $cjOrderService)
    {
        $this->cjOrderService = $cjOrderService;
    }

    public function sendOrderPage()
    {
        return view('order-result');
    }

    public function sendTestOrder()
    {
                $orderData = [
    "orderNumber" => "1234",
    "shippingZip" => "123",
    "shippingCountry" => "123",
    "shippingCountryCode" => "US",
    "shippingProvince" => "123",
    "shippingCity" => "132",
    "shippingCounty" => "",
    "shippingPhone" => "111",
    "shippingCustomerName" => "123213",
    "shippingAddress" => "123213",
    "shippingAddress2" => "123213",
    "taxId" => "123",
    "remark" => "note",
    "email" => "",
    "consigneeID" => "",
    "payType" => "",
    "shopAmount" => "",
    "logisticName" => "PostNL",
    "fromCountryCode" => "CN",
    "houseNumber" => "123",
    "iossType" => "",
    "platform" => "shopify",
    "iossNumber" => "",
    "shopLogisticsType" => 1,
    "storageId" => "201e67f6ba4644c0a36d63bf4989dd70",
    "products" => [
        [
            "vid" => "92511400-C758-4474-93CA-66D442F5F787",
            "quantity" => 1,
            "storeLineItemId" => "test-lineItemId-1111"
        ]
    ]
];


        $response = $this->cjOrderService->createOrder($orderData);

        return view('order-result', ['response' => $response]);
    }
}
