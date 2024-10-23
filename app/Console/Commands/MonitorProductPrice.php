<?php

namespace App\Console\Commands;

use App\Events\ProductMonitoredEvent;
use App\Models\MonitoringQueue;
use App\Models\Product;
use App\Services\MonitoringQueueService;
use App\Services\ProductService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MonitorProductPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monitor-product-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(MonitoringQueueService $monitoringQueueService, ProductService $productService)
    {
        $products = $productService->getProductsForMonitoring();


        foreach ($products as $product) {
           $monitoringQueueService->create($product['id']);
        }

        $queues = $monitoringQueueService->getQueuesForMonitor();

        if (!count($queues)) {
            $this->info('queue is empty');
            return;
        }

        $this->info('elements in queue:  ' . count($queues));
        foreach ($queues as $queue) {
            $queue->status = MonitoringQueue::STATUS_STARTED;
            $queue->save();
            $data = $monitoringQueueService->monitorPriceByUrl($queue);
            if ($data['success']) {
                $product = $queue->product;
                $product->price = $data['price'];
                $product->monitored_at = time();
                $product->save();

                event(new ProductMonitoredEvent($product));
            }
        }
    }
}
