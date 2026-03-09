<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\CJ\CJAuthService;
use Illuminate\Support\Facades\Auth;
class CJAuthController extends Controller
{
    protected CJAuthService $cjAuthService;

    public function __construct(CJAuthService $cjAuthService)
    {
        $this->cjAuthService = $cjAuthService;
    }


   public function redirect()
   {
            if (!Auth::check()) 
            {
                return redirect()->route('login');
            }
        return  $this->cjAuthService->redirect();
    } 


    public function callback(Request $request)
    {
        if (!$request->has('apiKey') || empty($request->apiKey)) {
            return redirect('/dashboard')->with('error', 'CJ authorization canceled or no API key.');
        }

        try {
            $data = $this->cjAuthService->handleCallback($request->apiKey, Auth::id());
            if (!$data) 
                { 
                 return redirect('/dashboard')
                 ->with('error', 'CJ connection failed: no data returned.');
               }

            return redirect('/dashboard')->with('success', 'CJ Connected Successfully!');

        } catch (\Exception $e) {
            Log::error('CJ Connect Error: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Error connecting CJ: ' . $e->getMessage());
        }
    }


     public function logoutAndDelete()
        {
            $userId = Auth::id(); 

            try {
                $this->cjAuthService->logoutAndDeleteAccount($userId);

               
                return redirect('/dashboard')->with('success', 'CJ account successfully logged out and deleted.');
            } catch (\Exception $e) {
              
                Log::error("CJ Logout Error for user {$userId}: " . $e->getMessage());
                return redirect('/dashboard')->with('error', 'Failed to logout CJ account: ' . $e->getMessage());
            }
        }

        public function logoutDeleteToken(){


            if($this->cjAuthService->logoutAndDeleteAccount(Auth::id())){
                return redirect('/dashboard')->with('success', 'Your account has been deleted successfully.');
            }

        }














/* public function callback(Request $request)
{

    $user = Auth::user();
    if (!$user) {
        return redirect('/dashboard')->with('error', 'User not authenticated.');
    }
    // 1️⃣ Check if apiKey is present
    if (!$request->has('apiKey') || empty($request->apiKey)) {
        return redirect('/dashboard')
            ->with('error', 'CJ authorization was canceled or no API key provided.');
    }

    $apiKey = $request->apiKey;

    try {
        // 2️⃣ Request access token from CJ
        $response = Http::post(
            'https://developers.cjdropshipping.com/api2.0/v1/authentication/getAccessToken',
            ['apiKey' => $apiKey]
        );
        throw_if($response->failed(), new \Exception('Failed to get CJ access token.'));

        if ($response->failed()) {
            return redirect('/dashboard')
                ->with('error', 'Failed to get CJ access token. Please try again.');
        }

        $data = $response->json()['data'];

        // 3️⃣ Save or update CJ account
        CJAccount::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'open_id' => $data['openId'],
                'access_token' => $data['accessToken'],
                'refresh_token' => $data['refreshToken'],
                'access_token_expires_at' => $data['accessTokenExpiryDate'],
                'refresh_token_expires_at' => $data['refreshTokenExpiryDate'],
            ]
        );

        return redirect('/dashboard')->with('success', 'CJ Connected Successfully!');
    } catch (\Exception $e) {

        // 4️⃣ Catch any unexpected errors
        \Log::error('CJ Connect Error: ' . $e->getMessage());
        return redirect('/dashboard')
            ->with('error', 'An error occurred while connecting CJ: ' . $e->getMessage());
    }
} */

/* public function callback(Request $request)
{
    if (!$request->has('apiKey')) {
        return view('cj-close', ['status' => 'cancelled']);
    }

    $apiKey = $request->apiKey;

    // Request CJ token and save to database
    $response = Http::post(
        'https://developers.cjdropshipping.com/api2.0/v1/authentication/getAccessToken',
        ['apiKey' => $apiKey]
    );

    $data = $response->json()['data'];

    CJAccount::updateOrCreate(
        ['user_id' => auth()->id()],
        [
            'open_id' => $data['openId'],
            'access_token' => $data['accessToken'],
            'refresh_token' => $data['refreshToken'],
            'access_token_expires_at' => $data['accessTokenExpiryDate'],
            'refresh_token_expires_at' => $data['refreshTokenExpiryDate'],
        ]
    );

    return view('cj-close', ['status' => 'success']);
}
 */

}
