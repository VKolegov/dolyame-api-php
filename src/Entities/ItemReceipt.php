<?php

namespace VKolegov\DolyameAPI\Entities;

use VKolegov\DolyameAPI\Contracts\Arrayable;

class ItemReceipt implements Arrayable
{
    private string $tax;
    private string $paymentMethod;
    private ?string $paymentObject = null;
    private ?string $measurementUnit = null;

    /**
     * @param string $tax
     * @param string $paymentMethod
     */
    public function __construct(string $tax, string $paymentMethod)
    {
        $this->tax = $tax;
        $this->paymentMethod = $paymentMethod;
    }

    public static function fromArray(array $arr): ItemReceipt
    {
        $itemReceipt = new self(
            $arr['tax'],
            $arr['payment_method']
        );

        if (isset($arr['payment_object'])) {
            $itemReceipt->setPaymentObject($arr['payment_object']);
        }

        if (isset($arr['measurement_unit'])) {
            $itemReceipt->setMeasurementUnit($arr['measurement_unit']);
        }

        return $itemReceipt;
    }

    public function setPaymentObject(string $paymentObject): ItemReceipt
    {
        $this->paymentObject = $paymentObject;
        return $this;
    }

    public function setMeasurementUnit(string $measurementUnit): ItemReceipt
    {
        $this->measurementUnit = $measurementUnit;
        return $this;
    }

    public function setTax(string $tax): ItemReceipt
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @param string $paymentMethod
     * @return ItemReceipt
     */
    public function setPaymentMethod(string $paymentMethod): ItemReceipt
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'tax' => $this->tax,
            'payment_method' => $this->paymentMethod,
            'payment_object' => $this->paymentObject ?? null,
            'measurement_unit' => $this->measurementUnit ?? null,
        ];
    }
}