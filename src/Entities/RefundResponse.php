<?php

namespace VKolegov\DolyameAPI\Entities;

use VKolegov\DolyameAPI\Contracts\Arrayable;

class RefundResponse implements Arrayable
{
    private float $amount;
    private string $id;

    public function __construct(string $id, float $amount)
    {
        $this->id = $id;
        $this->amount = $amount;
    }

    public static function fromArray(array $arr): RefundResponse
    {
        return new self(
            $arr['refund_id'],
            floatval($arr['amount']),
        );
    }

    /**
     * Идентификатор возврата в Dolyame.ru
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Общая сумма подлежащая выплате клиентом через Dolyame.ru после возврата
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->getAmount(),
            'refund_id' => $this->getId(),
        ];
    }
}