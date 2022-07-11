<?php

namespace VKolegov\DolyameAPI\Requests;

use VKolegov\DolyameAPI\Contracts\Arrayable;
use VKolegov\DolyameAPI\Entities\OrderItems;

abstract class AbstractOrderRequest implements Arrayable
{
    protected string $id;

    protected float $amount;
    protected ?float $prepaidAmount = null;

    /**
     * @var \VKolegov\DolyameAPI\Entities\OrderItems
     */
    protected OrderItems $items;

    public function __construct(
        string     $id,
        float      $amount,
        OrderItems $items
    )
    {

        $this->id = $id;
        $this->amount = $amount;
        $this->items = $items;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrepaidAmount(): ?float
    {
        return $this->prepaidAmount;
    }

    public function setPrepaidAmount(float $prepaidAmount): self
    {
        $this->prepaidAmount = $prepaidAmount;
        return $this;
    }

    /**
     * @return \VKolegov\DolyameAPI\Entities\OrderItems
     */
    public function getItems(): OrderItems
    {
        return $this->items;
    }

    public function setItems(OrderItems $items): self
    {
        $this->items = $items;
        return $this;
    }
}