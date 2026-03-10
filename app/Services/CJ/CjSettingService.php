<?php

namespace App\Services\CJ;

use App\Models\CJAccount;
use Illuminate\Support\Facades\Http;

class CjSettingService
{
    protected CJAccount $cjaccount;
    protected $token;
    protected $baseUrl = "https://developers.cjdropshipping.com/api2.0/v1/setting/get";
    public function __construct(CJAccount $cjaccount)
    {

       $this->cjaccount = $cjaccount;
        
    }

    protected function client(){
        return Http::withHeaders([
            'CJ-Access-Token' => $this->token,
            'Content-Type' => 'application/json'
        ]);
    }

    public function CjSetting($user_id){
       try{
        $this->cjaccount = CJAccount::where("user_id" ,$user_id)->first();

        if ($this->cjaccount && $this->cjaccount->access_token) {
                        $this->token = $this->cjaccount->access_token;
                } else {
                $this->token = null; 
            } 
        $response = $this->client()->get( $this->baseUrl);

        if ($response->failed() || ($response->json('code') ?? 200) != 200) {
            return [
                'success' => false,
                'message' => $response->json('message') ?? 'An error occurred while connecting to CJ',
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
