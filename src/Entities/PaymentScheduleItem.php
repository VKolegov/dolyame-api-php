<?php

namespace VKolegov\DolyameAPI\Entities;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use VKolegov\DolyameAPI\Contracts\Arrayable;

class PaymentScheduleItem implements Arrayable
{
    private DateTimeInterface $paymentDate;
    private float $amount;
    private string $status;

    public function __construct(DateTimeInterface $paymentDate, float $amount, string $status)
    {
        $this->paymentDate = $paymentDate;
        $this->amount = $amount;
        $this->status = $status;
    }

    public static function fromArray(array $arr): PaymentScheduleItem
    {
        $paymentDate = DateTime::createFromFormat(
            'Y-m-d', $arr['payment_date']
        );

        if (!$paymentDate) {
            throw new InvalidArgumentException(
                "payment_date should be in 2016-05-23 (Y-m-d) format"
            );
        }

        return new self(
            $paymentDate, floatval($arr['amount']), $arr['status']
        );
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPaymentDate(): DateTimeInterface
    {
        return $this->paymentDate;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return [
            'payment_date' => $this->paymentDate->format('Y-m-d'),
            'amount' => $this->amount,
            'status' => $this->status,
        ];
    }
}