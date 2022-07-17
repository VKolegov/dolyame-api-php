<?php

namespace VKolegov\DolyameAPI\Entities;

use VKolegov\DolyameAPI\Contracts\Arrayable;

class Item implements Arrayable
{
    private string $name;
    private int $quantity;
    private float $price;
    private ?string $sku = null;
    private ?ItemReceipt $receipt = null;

    /**
     * @param string $name
     * @param int $quantity
     * @param float $price
     */
    public function __construct(string $name, int $quantity, float $price)
    {

        $this->name = $name;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public static function fromArray(array $arr): Item
    {
        $item = new self(
            $arr['name'],
            intval($arr['quantity']),
            floatval($arr['price']),
        );

        if (isset($arr['sku'])) {
            $item->setSku($arr['sku']);
        }

        if (isset($arr['receipt'])) {
            $item->setReceipt(
                ItemReceipt::fromArray($arr['receipt'])
            );
        }

        return $item;
    }

    public function setName(string $name): Item
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @param string|null $sku
     * @return Item
     */
    public function setSku(?string $sku): Item
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @param \VKolegov\DolyameAPI\Entities\ItemReceipt|null $receipt
     * @return Item
     */
    public function setReceipt(?ItemReceipt $receipt): Item
    {
        $this->receipt = $receipt;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'sku' => $this->sku ?? null,
            'receipt' => $this->receipt ? $this->receipt->toArray() : null
        ];
    }
}