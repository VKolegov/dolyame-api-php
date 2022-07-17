<?php

namespace VKolegov\DolyameAPI\Requests;

class RefundRequest extends AbstractOrderRequest
{
    // TODO: what is it?
    private ?array $fiscalizationSettings = null;

    public function setFiscalizationSettings(array $fiscalizationSettings): RefundRequest
    {
        $this->fiscalizationSettings = $fiscalizationSettings;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'fiscalization_settings' => $this->fiscalizationSettings,
            'returned_items' => $this->items->toArray(),
            'refunded_prepaid_amount' => $this->prepaidAmount ?? 0,
        ];
    }
}