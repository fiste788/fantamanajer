<?php

namespace StreamCake;

use Cake\Chronos\Chronos;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Inflector;

class Enrich implements EnrichInterface
{
    use LocatorAwareTrait;

    /**
     * @var array
     */
    private $enrichingFields = ['actor', 'object'];

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function setEnrichingFields(array $fields)
    {
        $this->enrichingFields = $fields;

        return $this;
    }

    /**
     * @param array $activities
     *
     * @return EnrichedActivity[]
     */
    public function enrichActivities(array $activities)
    {
        if (empty($activities)) {
            return [];
        }

        $activities = $this->wrapActivities($activities);
        $references = $this->collectReferences($activities);
        $objects = $this->retrieveObjects($references);

        return $this->injectObjects($activities, $objects);
    }

    /**
     * @param array $aggregatedActivities
     *
     * @return EnrichedActivity[]
     */
    public function enrichAggregatedActivities(array $aggregatedActivities)
    {
        if (empty($aggregatedActivities)) {
            return [];
        }

        $allActivities = [];

        foreach ($aggregatedActivities as &$aggregated) {
            $activities = $this->wrapActivities($aggregated['activities']);
            $allActivities = array_merge($allActivities, $activities);

            $aggregated['activities'] = $activities;
            $aggregated['created_at'] = new Chronos($aggregated['created_at']);
            $aggregated['updated_at'] = new Chronos($aggregated['updated_at']);
        }

        $references = $this->collectReferences($allActivities);
        $objects = $this->retrieveObjects($references);
        $this->injectObjects($allActivities, $objects);

        return $aggregatedActivities;
    }

    /**
     * @param array $activities
     *
     * @return EnrichedActivity[]
     */
    private function wrapActivities(array $activities)
    {
        return array_map(function (array $activity) {
            return new EnrichedActivity($activity);
        }, $activities);
    }

    /**
     * @param EnrichedActivity[] $activities
     *
     * @return array
     */
    private function collectReferences(array $activities)
    {
        $references = [];

        foreach ($activities as $activity) {
            foreach ($activity as $field => $value) {
                if ($value === null) {
                    continue;
                }

                if (!in_array($field, $this->enrichingFields)) {
                    continue;
                }

                list($type, $identifier) = explode(':', $value);
                $references[$type][] = $identifier;
            }
        }

        return $references;
    }

    /**
     * @param array $references
     *
     * @return array
     */
    private function retrieveObjects(array $references)
    {
        $objects = [];

        foreach (array_keys($references) as $type) {
            $identifiers = array_unique($references[$type]);

            $plural = Inflector::pluralize($type);
            $table = $this->getTableLocator()->get($plural);
            $result = $table->find('list', [
                'keyField' => 'id',
                'valueField' => function ($obj) {
                    return $obj;
                }
            ])->whereInList('id', $identifiers)->toArray();

            $objects[$type] = $result;
        }

        return $objects;
    }

    /**
     * @param EnrichedActivity[] $activities
     * @param array $objects
     *
     * @return EnrichedActivity[]
     */
    public function injectObjects($activities, $objects)
    {
        foreach ($activities as &$activity) {
            foreach ($this->enrichingFields as $field) {
                if (!isset($activity[$field])) {
                    continue;
                }

                $value = $activity[$field];
                list($type, $identifier) = explode(':', $value);

                if (!isset($objects[$type], $objects[$type][$identifier])) {
                    $activity->trackNotEnrichedField($type, $identifier);
                    continue;
                }

                $activity[$field] = $objects[$type][$identifier];
            }
        }

        return $activities;
    }
}
