<?php
declare(strict_types=1);

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;

class MissingPlayerInLineupRule
{
    public function __invoke(EntityInterface $entity, array $options)
    {
        for ($i = 0; $i < 11; $i++) {
            if (!array_key_exists($i, $entity->dispositions)) {
                return false;
            } elseif ($entity->dispositions[$i]->position != ($i + 1)) {
                return false;
            }
        }

        return true;
    }
}
