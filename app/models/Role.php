<?php

namespace Fantamanajer\Models;

class Role extends Table\RolesTable{

    public static function getListByAbbreviation() {
        $roles = self::getList();
        $array = array();
        foreach($roles as $role) {
            $array[$role->abbreviation] = $role;
        }
        return $array;
    }
}

 