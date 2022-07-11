<?php

namespace VKolegov\DolyameAPI\Entities;

use VKolegov\DolyameAPI\Contracts\Arrayable;

class RefundItems implements Arrayable
{
    /**
     * @var array|\VKolegov\DolyameAPI\Entities\RefundItem[]
     */
    private array $items = [];

    public static function fromArray(array $arr): RefundItems
    {
        $obj = new self();

        foreach ($arr as $item) {
            $obj->addItem(
                RefundItem::fromArray($item)
            );
        }

        return $obj;
    }

    public function addItem(RefundItem $item): RefundItems
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @return array|\VKolegov\DolyameAPI\Entities\RefundItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array|\VKolegov\DolyameAPI\Entities\RefundItem[] $items
     * @return \VKolegov\DolyameAPI\Entities\RefundItems
     */
    public function setItems(array $items): RefundItems
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
            fn(RefundItem $item) => $item->toArray(),
            $this->items
        );
    }
}