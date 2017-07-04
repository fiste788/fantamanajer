<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Club Entity.
 *
 * @property int $id
 * @property string $name
 * @property string $partitive
 * @property string $determinant
 * @property \App\Model\Entity\Member[] $members
 */
class Club extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
    
    protected function _getAbbreviation() {
        return strtoupper(substr($this->name, 0,3));
    }
}
