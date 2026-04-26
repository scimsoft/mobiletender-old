<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Async thermal print job. When PRINTING_ASYNC=true, dispatch this instead of
 * blocking the HTTP request on the printer bridge.
 */
class PrintReceiptJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $ticketId,
        public string $kind = 'order'
    ) {
    }

    public function handle(): void
    {
        Log::info('PrintReceiptJob: queued print (implement PrinterService wiring)', [
            'ticket' => $this->ticketId,
            'kind' => $this->kind,
        ]);
    }
}
