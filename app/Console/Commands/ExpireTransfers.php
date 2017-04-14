<?php

namespace App\Console\Commands;

use App\Classes\WalletManager;
use App\Transfer;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Money\Money;

class ExpireTransfers extends Command
{

    /**
     * Days the token expires in.
     *
     * @var int
     */
    protected $expire_in = 3;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mbarter:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expires transfers after a certain date.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $transfers = Transfer::where('created_at', '<=', Carbon::now()->subDays($this->expire_in))
            ->where('status', 'pending')
            ->with('senderWallet.user')->get();

        foreach ($transfers as $transfer) {
            $this->cancel($transfer);
        }

        logger()->info(count($transfers) . ' transfers expired.');
    }

    /**
     * Processes a cancel request.
     *
     * @param Transfer $transfer
     */
    protected function cancel(Transfer $transfer)
    {
        $transfer->update(['status' => 'cancelled']);

        $sender = $transfer->senderWallet->user;
        $this->makeDeposit($sender, $transfer);
    }

    /**
     * Process deposit to the desired user.
     *
     * @param User $user
     * @param Transfer $transfer
     * @return \App\Wallet
     */
    protected function makeDeposit(User $user, Transfer $transfer)
    {
        $manager = new WalletManager($user);

        $code = $transfer->senderWallet->currency_code;
        $wallet =  $manager->deposit(Money::$code($transfer->amount));

        $manager->record($transfer, $wallet, true);

        return $wallet;
    }

}
