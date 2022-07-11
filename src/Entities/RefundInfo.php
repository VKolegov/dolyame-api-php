<?php

namespace VKolegov\DolyameAPI\Entities;

use VKolegov\DolyameAPI\Contracts\Arrayable;

class RefundInfo implements Arrayable
{
    private float $totalRefundedAmount;
    private float $totalRefundedPrepaidAmount;
    private RefundItems $items;

    public function __construct(
        float       $totalRefundedAmount,
        float       $totalRefundedPrepaidAmount,
        RefundItems $items
    )
    {

        $this->totalRefundedAmount = $totalRefundedAmount;
        $this->totalRefundedPrepaidAmount = $totalRefundedPrepaidAmount;
        $this->items = $items;
    }

    public static function fromArray(array $arr): RefundInfo
    {
        $items = RefundItems::fromArray($arr['items']);

        return new self(
            floatval($arr['total_refunded_amount']),
            floatval($arr['total_refunded_prepaid_amount']),
            $items
        );
    }

    /**
     * Общая сумма возвратов
     * @return float
     */
    public function getTotalRefundedAmount(): float
    {
        return $this->totalRefundedAmount;
    }

    /**
     * Общая сумма возвращенной предоплаты другим способом, отличным от Dolyame
     * @return float
     */
    public function getTotalRefundedPrepaidAmount(): float
    {
        return $this->totalRefundedPrepaidAmount;
    }

    /**
     * Список возвратов
     * @return \VKolegov\DolyameAPI\Entities\RefundItems
     */
    public function getItems(): RefundItems
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return [
            'total_refunded_amount' => $this->totalRefundedAmount,
            'total_refunded_prepaid_amount' => $this->totalRefundedPrepaidAmount,
            'items' => $this->items->toArray(),
        ];
    }
}