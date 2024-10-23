<?php

namespace App\Http\Controllers;

use App\Helpers\OlxHelper;
use App\Http\Requests\SubscriptionRequest;
use App\Services\MonitoringQueueService;
use App\Services\ProductService;
use App\Services\SubscriptionService;
use App\Services\UserService;

class SubscriptionController extends Controller
{
    public function subscribe(
        SubscriptionRequest $request,
        UserService $userService,
        ProductService $productService,
        SubscriptionService $subscriptionService,
        MonitoringQueueService $monitoringQueueService
    )
    {
        $validatedData = $request->validated();
        $user = $userService->findByEmail($validatedData['email']);
        $responseMessage = 'Your subscription created';
        $status = 200;
        $validatedData['url'] = explode('?', $validatedData['url'])[0];

        if (!$user) {
            $user = $userService->create($validatedData['email']);
            $responseMessage = 'We sent activation email to your address, please check your email for verifying';
        } else {

            if (!$user->is_active) {
                $responseMessage = 'We sent activation email to your address, please check your email for verifying';
                $status = 400;
            }

            if (!OlxHelper::isRightDomain($validatedData['url'])) {
                $responseMessage = 'Incorrect url';
                $status = 400;
            } else if (!OlxHelper::getJsonScriptFromContent($validatedData['url'])) {
                $responseMessage = 'Is not product page';
                $status = 400;
            } else {
                $product = $productService->findByUrl($validatedData['url']);

                if (!$product) {
                    $product = $productService->create($validatedData['url']);
                } else if ($subscriptionService->findByUserAndProduct($user->id, $product->id)) {
                    $responseMessage = 'You already subscribed for this product';
                    $status = 400;
                }

                $subscriptionService->create($user, $product);
            }
        }

        return response()->json(['message' => $responseMessage], $status);

    }
}
