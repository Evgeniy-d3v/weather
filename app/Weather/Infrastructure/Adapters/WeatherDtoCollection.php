<?php
namespace App\Weather\Infrastructure\Adapters;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

final class WeatherDtoCollection implements Countable, IteratorAggregate, JsonSerializable
{
    /**
     * @var WeatherDto[]
     */
    private array $items = [];

    /**
     * @param WeatherDto[] $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $weather) {
            $this->add($weather);
        }
    }

    public function add(WeatherDto $weather): void
    {
        $this->items[] = $weather;
    }

    /**
     * @return WeatherDto[]
     */
    public function all(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }
}
