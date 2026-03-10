<?php

namespace App\Services\CJ;

use App\Models\CJAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CjOrderService
{
    protected CJAccount $account;
    protected $token ;
    protected $baseUrl = "https://developers.cjdropshipping.com/api2.0/v1";

    public function __construct(CJAccount $account)
    {
        $this->account = $account;
        $account = CjAccount::where('user_id', Auth::id())->first();
         if ($account && $account->access_token) {
            $this->token = $account->access_token;
        } else {
            $this->token = null; 
        }
        
    }

    protected function client()
    {
        return Http::withHeaders([
            'CJ-Access-Token' => $this->token,
            'Content-Type' => 'application/json'
        ]);
    }

    public function createOrder(array $orderData)
    {
        try{
            
            $response = $this->client()->post(
                        $this->baseUrl . '/shopping/order/createOrderV2',
                        $orderData
                    );
        if ($response->failed() || ($response->json('code') ?? 200) != 200) {
            return [
                'success' => false,
                'message' => $response->json('message') ?? 'حدث خطأ غير معروف في CJ',
                'data' => $response->json()
            ];
        }

        return [
            'success' => true,
            'data' => $response->json()
        ];

        }catch (\Exception $e) {
        return [
            'success' => false,
            'message' => 'An error occurred while connecting to CJ:' . $e->getMessage()
        ];
    }
       
    }
}
