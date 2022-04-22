<?php

declare(strict_types=1);

namespace StreamCake;

use ArrayAccess;
use ArrayIterator;
use Cake\I18n\FrozenTime;
use IteratorAggregate;

class EnrichedActivity implements IteratorAggregate, ArrayAccess
{
    /**
     * @var array
     */
    private $activityData = [];

    /**
     * @var array
     */
    private $notEnrichedData = [];

    /**
     * @param array $activityData
     */
    public function __construct(array $activityData)
    {
        if (isset($activityData['time'])) {
            /** @var string $time */
            $time = $activityData['time'];
            $activityData['time'] = new FrozenTime($time, 'UTC');
        }
        $this->activityData = $activityData;
    }

    /**
     * @param string $field
     * @param mixed $value
     */
    public function trackNotEnrichedField(mixed $field, mixed $value): void
    {
        $this->notEnrichedData[$field] = $value;
    }

    /**
     * @return array
     */
    public function getNotEnrichedData(): array
    {
        return $this->notEnrichedData;
    }

    /**
     * @return bool
     */
    public function enriched()
    {
        return empty($this->notEnrichedData);
    }

    // ArrayAccess implementation methods

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->activityData[] = $value;
        } else {
            $this->activityData[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->activityData[$offset]);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->activityData[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->activityData[$offset] ?? null;
    }

    /**
     * Retrieve an external iterator
     *
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->activityData);
    }
}
