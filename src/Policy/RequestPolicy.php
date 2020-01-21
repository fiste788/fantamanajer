<?php
declare(strict_types=1);

namespace App\Policy;

class RequestPolicy
{
    /**
     * Method to check if the request can be accessed
     *
     * @param null|\App\Model\Entity\User $identity Identity
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return bool
     */
    public function canAccess($identity, \Cake\Http\ServerRequest $request)
    {
        if ($request->getParam('prefix') === 'Admin' && $identity != null && !$identity->admin) {
            return false;
        }

        if ($request->getParam('prefix') === 'Championships') {
            $championshipId = (int)$request->getParam('championship_id');

            return $identity != null && $identity->isInChampionship($championshipId);
        }

        return true;
    }
}
