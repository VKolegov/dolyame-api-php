<?php

namespace VKolegov\DolyameAPI\Entities;

use VKolegov\DolyameAPI\Contracts\Arrayable;

class OrderInfo implements Arrayable
{
    private ?string $id = null;
    private string $status;
    private float $amount;
    private float $residualAmount;
    private ?string $link = null;
    private ?PaymentSchedule $paymentSchedule = null;
    private ?RefundInfo $refundInfo = null;

    public function __construct(
        string $status,
        float  $amount,
        float  $residualAmount)
    {

        $this->status = $status;
        $this->amount = $amount;
        $this->residualAmount = $residualAmount;
    }

    public static function fromArray(array $arr): OrderInfo
    {
        $obj = new self(
            $arr['status'],
            floatval($arr['amount']),
            floatval($arr['residual_amount']),
        );

        if (isset($arr['id'])) {
            $obj->setId($arr['id']);
        }

        if (isset($arr['link'])) {
            $obj->setLink($arr['link']);
        }

        if (isset($arr['payment_schedule'])) {
            $obj->setPaymentSchedule(
                PaymentSchedule::fromArray($arr['payment_schedule'])
            );
        }

        if (isset($arr['refund_info'])) {
            $obj->setRefundInfo(
                RefundInfo::fromArray($arr['refund_info'])
            );
        }

        return $obj;
    }

    /**
     * Getters
     */

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return OrderInfo
     */
    public function setId(?string $id): OrderInfo
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Статус заказа
     * committed, wait_for_commit, new, rejected, approved, canceled, completed
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Общая сумма подлежащая оплате клиентом
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Сумма, которая подлежит погашению клиентом
     * @return float
     */
    public function getResidualAmount(): float
    {
        return $this->residualAmount;
    }

    /**
     * Ссылка на форму заказа на сайтe Dolyame.ru
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     * @return OrderInfo
     */
    public function setLink(?string $link): OrderInfo
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Setters
     */

    /**
     * Расписание платежей клиента
     * @return \VKolegov\DolyameAPI\Entities\PaymentSchedule|null
     */
    public function getPaymentSchedule(): ?PaymentSchedule
    {
        return $this->paymentSchedule;
    }

    /**
     * @param \VKolegov\DolyameAPI\Entities\PaymentSchedule|null $paymentSchedule
     * @return OrderInfo
     */
    public function setPaymentSchedule(?PaymentSchedule $paymentSchedule): OrderInfo
    {
        $this->paymentSchedule = $paymentSchedule;
        return $this;
    }

    /**
     * @return \VKolegov\DolyameAPI\Entities\RefundInfo|null
     */
    public function getRefundInfo(): ?RefundInfo
    {
        return $this->refundInfo;
    }

    /**
     * @param \VKolegov\DolyameAPI\Entities\RefundInfo|null $refundInfo
     * @return OrderInfo
     */
    public function setRefundInfo(?RefundInfo $refundInfo): OrderInfo
    {
        $this->refundInfo = $refundInfo;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'amount' => $this->amount,
            'residual_amount' => $this->residualAmount,
            'link' => $this->link,
            'payment_schedule' => $this->paymentSchedule ? $this->paymentSchedule->toArray() : null,
            'refund_info' => $this->refundInfo ? $this->refundInfo->toArray() : null,
        ];
    }
}
