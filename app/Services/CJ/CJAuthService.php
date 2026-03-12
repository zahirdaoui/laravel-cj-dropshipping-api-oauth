<?php

namespace App\Services\CJ;

use Illuminate\Support\Facades\Http;
use App\Models\CJAccount;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class CJAuthService
{
    
    protected CJAccount $account;
    protected $redirect_endpoint="https://www.cjdropshipping.com/mine/authorize/erpAuthorization?";
    protected $auth_endpoint = "https://developers.cjdropshipping.com/api2.0/v1/authentication";



    public function __construct(CJAccount $account)
    {
        $this->account = $account;
    }


    public function redirect()
   {    
            $userId = Auth::id();
            $userName = Auth::user()->name;

        $url = $this->redirect_endpoint. http_build_query([
            'clientId' => config('services.cj.client_id'),
            'partnerName' => config('services.cj.partner_name'),
            'clientAccountId' => $userId,
            'clientAccountName' => $userName,
            'state' => csrf_token(),
            'clientRedirectUrl' => route('cj.callback') 
        ]);

            return redirect($url);
    }


    public function handleCallback(string $apiKey, int $userId): array
    {
        $response = Http::post(
            $this->auth_endpoint.'/getAccessToken',
            ['apiKey' => $apiKey]
        );

        throw_if($response->failed(), new \Exception('Failed to get CJ access token.'));

        $data = $response->json()['data'];

        $account = CJAccount::updateOrCreate(
            ['user_id' => $userId],
            [
                'open_id' => $data['openId'],
                'access_token' => $data['accessToken'],
                'refresh_token' => $data['refreshToken'],
                'access_token_expires_at' => $data['accessTokenExpiryDate'],
                'refresh_token_expires_at' => $data['refreshTokenExpiryDate'],
            ]
        );

        return $account;
    }


    


     public function logoutAndDeleteAccount($userId)
    {
        $account = CJAccount::where('user_id', $userId)->first();

        if (!$account) {
            throw new \Exception("No CJ account found for user id {$userId}");
        }

       
        $response = Http::withHeaders([
            'CJ-Access-Token' => $account->access_token,
        ])->post('https://developers.cjdropshipping.com/api2.0/v1/authentication/logout');

        $data = $response->json();

        if (!isset($data['data']) || $data['code'] != 200) {
            throw new \Exception("CJ Logout failed: " . ($data['message'] ?? 'Unknown error'));
        }

        $account->delete();

        return true;
    }


    public function refreshTokenForUser($userId)
    {
        $account = CJAccount::where('user_id', $userId)->first();

        if (!$account) {
            throw new \Exception("No CJ account found for user id {$userId}");
        }

        $response = Http::post($this->auth_endpoint."/refreshAccessToken", [
            'refreshToken' => $account->refresh_token
        ]);

        $data = $response->json();

        if (!isset($data['data']) || $data['code'] != 200) {
            throw new \Exception("CJ Refresh Token failed: " . ($data['message'] ?? 'Unknown error'));
        }

        $account->access_token = $data['data']['accessToken'];
        $account->access_token_expires_at = Carbon::parse($data['data']['accessTokenExpiryDate']);
        $account->refresh_token = $data['data']['refreshToken'];
        $account->refresh_token_expires_at = Carbon::parse($data['data']['refreshTokenExpiryDate']);
        $account->save();

        return $account;
    }

    public function needsRefresh(CJAccount $account)
    {
        return Carbon::now()
        ->addHours(47)
        ->addMinutes(59)
        ->gte($account->access_token_expires_at);
    }


}
