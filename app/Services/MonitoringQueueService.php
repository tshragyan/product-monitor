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

    public function getByProductId(int $productId): MonitoringQueue
    {
        return MonitoringQueue::where('product_id', $productId)->first();
    }

    public function getQueuesForMonitor()
    {
        return MonitoringQueue::where('status', MonitoringQueue::STATUS_PENDING)->get();
    }
}
