<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Cake\Http\ServerRequest;

class RequestPolicy
{
    /**
     * Method to check if the request can be accessed
     *
     * @param \App\Model\Entity\User|null $identity Identity
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return bool
     */
    public function canAccess(?User $identity, ServerRequest $request): bool
    {
        if (strtolower((string)$request->getParam('prefix')) === 'admin' && $identity != null && !$identity->admin) {
            return false;
        }

        if (strtolower((string)$request->getParam('prefix')) === 'championships') {
            $championshipId = (int)$request->getParam('championship_id');

            return $identity != null && $identity->isInChampionship($championshipId);
        }

        return true;
    }
}
