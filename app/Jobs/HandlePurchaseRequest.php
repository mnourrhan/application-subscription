<?php

namespace App\Jobs;

use App\Models\Device;
use App\Services\PurchaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class HandlePurchaseRequest
 * @package App\Jobs
 * @author Nourhan
 */
class HandlePurchaseRequest implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Device
     */
    protected $device = Null;

    /**
     * @var string
     */
    protected $receipt = '';

    /**
     * HandlePurchaseRequest constructor.
     */
    public function __construct($device, $receipt)
    {
        $this->device = $device;
        $this->receipt = $receipt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        app(PurchaseService::class)->execute($this->device, $this->receipt);
    }
}
