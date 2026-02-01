<?php

namespace App\Location\Application\DTO;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;

final class WeatherHourlyDtoCollection implements Countable, IteratorAggregate, JsonSerializable
{
    /**
     * @var WeatherHourlyDto[]
     */
    private array $items = [];

    private string $date;

    /**
     * @param  WeatherHourlyDto[]  $items
     */
    public function __construct(string $date, array $items = [])
    {
        $this->date = $date;
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function add(WeatherHourlyDto $dto): void
    {
        $this->items[] = $dto;
    }

    public function date(): string
    {
        return $this->date;
    }

    /**
     * @return WeatherHourlyDto[]
     */
    public function all(): array
    {
        return $this->items;
    }

    public function getIterator(): ArrayIterator
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

    public function first(): ?WeatherHourlyDto
    {
        return $this->items[0] ?? null;
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }

    public function toStorageArray(): array
    {
        $result = [];

        foreach ($this->items as $dto) {
            $result[$dto->time] = $dto->toStorageArray();
        }

        return $result;
    }
}
