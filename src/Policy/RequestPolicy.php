<?php
declare(strict_types=1);

namespace App\Policy;

class RequestPolicy
{
    /**
     * Method to check if the request can be accessed
     *
     * @param null|\Authorization\IdentityInterface $identity Identity
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return bool
     */
    public function canAccess($identity, \Cake\Http\ServerRequest $request)
    {
        if ($request->getParam('prefix') === 'Admin' && !$identity->admin) {
            return false;
        }

        if ($request->getParam('prefix') === 'Championships') {
            $championshipId = $request->getParam('championship_id');

            return $identity->isInChampionship($championshipId);
        }

        return true;
    }
}
