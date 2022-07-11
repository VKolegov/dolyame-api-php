<?php

namespace VKolegov\DolyameAPI\Entities;

use VKolegov\DolyameAPI\Contracts\Arrayable;

class PaymentSchedule implements Arrayable
{
    /**
     * @var array|\VKolegov\DolyameAPI\Entities\PaymentScheduleItem[]
     */
    private array $items = [];

    public static function fromArray(array $arr): PaymentSchedule
    {
        $obj = new self();

        foreach ($arr as $item) {
            $obj->addItem(
                PaymentScheduleItem::fromArray($item)
            );
        }

        return $obj;
    }

    public function addItem(PaymentScheduleItem $item): PaymentSchedule
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @return array|\VKolegov\DolyameAPI\Entities\PaymentScheduleItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array|\VKolegov\DolyameAPI\Entities\PaymentScheduleItem[] $items
     * @return \VKolegov\DolyameAPI\Entities\PaymentSchedule
     */
    public function setItems(array $items): PaymentSchedule
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return array_map(
            fn(PaymentScheduleItem $item) => $item->toArray(),
            $this->items
        );
    }
}