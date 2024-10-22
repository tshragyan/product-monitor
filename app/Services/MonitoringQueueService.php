<?php


namespace App\Services;


use App\Helpers\OlxHelper;
use App\Models\MonitoringQueue;
use GuzzleHttp\Client;

class MonitoringQueueService
{
    public function monitorPriceByUrl(MonitoringQueue $queue): array
    {
        $jsonData = OlxHelper::getJsonScriptFromContent($queue->product->url);
        $data['success'] = false;

        if ($jsonData) {
            $data['success'] = true;
            $data['price'] = $jsonData['offers'][0] ?? $jsonData['offers']['price'];
        }

        $queue->status = $data['success'] ? MonitoringQueue::STATUS_SUCCESS : MonitoringQueue::STATUS_FAILED;
        $queue->save();

        return $data;
    }

    public function create(int $productId): void
    {
        $monitoringQueue = new MonitoringQueue();
        $monitoringQueue->product_id = $productId;
        $monitoringQueue->status = MonitoringQueue::STATUS_PENDING;
        $monitoringQueue->save();
    }

    public function getQueuesForMonitor()
    {
        return MonitoringQueue::where('status', MonitoringQueue::STATUS_PENDING)->get();
    }
}
