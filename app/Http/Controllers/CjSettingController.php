<?php

namespace App\Http\Controllers;

use App\Services\CJ\CjSettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CjSettingController extends Controller
{
    public CjSettingService $cjsetting;

    public function __construct(CjSettingService $cjsetting)
    {
        $this->cjsetting = $cjsetting;
    }

    public function setting(){
        $data = $this->cjsetting->CjSetting(Auth::id())['data'];
        //dd($data);
        return view('setting', compact('data'));
    }
}
