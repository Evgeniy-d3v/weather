<?php

namespace App\TelegramBot\Domain\Entities;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

final class ClientEntityCollection implements Countable, IteratorAggregate, JsonSerializable
{
    /** @var ClientEntity[] */
    private array $items = [];

    /**
     * @param  ClientEntity[]  $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public static function fromArray(array $items): self
    {
        return new self($items);
    }

    public function add(ClientEntity $entity): void
    {
        $this->items[] = $entity;
    }

    /**
     * @return ClientEntity[]
     */
    public function all(): array
    {
        return $this->items;
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function first(): ?ClientEntity
    {
        return $this->items[0] ?? null;
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
