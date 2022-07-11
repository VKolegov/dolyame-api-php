<?php

namespace VKolegov\DolyameAPI\Requests;

use VKolegov\DolyameAPI\Entities\ClientInfo;

class CreateOrderRequest extends AbstractOrderRequest
{
    // TODO: what is it?
    private ?array $fiscalizationSettings = null;
    /**
     * @var \VKolegov\DolyameAPI\Entities\ClientInfo|null
     */
    private ?ClientInfo $clientInfo = null;
    private ?string $notificationURL = null;
    private string $failURL;
    private string $successURL;

    public function setFiscalizationSettings(array $fiscalizationSettings): CreateOrderRequest
    {
        $this->fiscalizationSettings = $fiscalizationSettings;
        return $this;
    }

    public function setClientInfo(ClientInfo $clientInfo): CreateOrderRequest
    {
        $this->clientInfo = $clientInfo;
        return $this;
    }

    public function setNotificationURL(string $notificationURL): CreateOrderRequest
    {
        $this->notificationURL = $notificationURL;
        return $this;
    }

    public function setFailURL(string $failURL): CreateOrderRequest
    {
        $this->failURL = $failURL;
        return $this;
    }

    public function setSuccessURL(string $successURL): CreateOrderRequest
    {
        $this->successURL = $successURL;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'order' => [
                'id' => $this->id,
                'amount' => $this->amount,
                'prepaid_amount' => $this->prepaidAmount ?? 0,
                'fiscalization_settings' => $this->fiscalizationSettings,
                'items' => $this->items->toArray(),
                'client_info' => $this->clientInfo->toArray(),
            ],
            'notification_url' => $this->notificationURL,
            'fail_url' => $this->failURL,
            'success_url' => $this->successURL,
        ];
    }
}