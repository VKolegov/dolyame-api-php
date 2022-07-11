<?php

namespace VKolegov\DolyameAPI\Entities;

use VKolegov\DolyameAPI\Contracts\Arrayable;

class OrderItems implements Arrayable
{
    /**
     * @var array|\VKolegov\DolyameAPI\Entities\Item[]
     */
    private array $items = [];

    public static function fromArray(array $arr): OrderItems
    {
        $obj = new self();

        foreach ($arr as $item) {
            $obj->addItem(
                Item::fromArray($item)
            );
        }

        return $obj;
    }

    public function addItem(Item $item): OrderItems
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @return array|\VKolegov\DolyameAPI\Entities\Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array|\VKolegov\DolyameAPI\Entities\Item[] $items
     * @return OrderItems
     */
    public function setItems(array $items): OrderItems
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
            fn(Item $item) => $item->toArray(),
            $this->items
        );
    }
}