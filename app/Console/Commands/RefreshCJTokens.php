<?php

namespace App\Console\Commands;

use App\Models\CJAccount;
use App\Services\CJ\CJAuthService;
use Illuminate\Console\Command;

class RefreshCJTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-c-j-tokens';

    /**
     * The console command description.
     *
     * @var string
    */
    protected $description = 'Command description';

    protected CJAuthService $cjAuthService;

    public function __construct(CJAuthService $cjAuthService)
    {
          parent::__construct();
        $this->cjAuthService = $cjAuthService;
    }

    /**
     * Execute the console command.
    */
    public function handle()
    {
         $accounts = CJAccount::all();

        foreach ($accounts as $account) {
            $this->cjAuthService->refreshTokenForUser($account->user_id);
        }

        $this->info('CJ tokens refreshed successfully');
    }
}
