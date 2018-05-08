<?php

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class FreePlayerFilteringForm extends Form
{
    protected function _buildSchema(Schema $schema)
    {
        return $schema->addField('enough', 'double')
            ->addField('match', ['type' => 'integer']);
    }

    protected function _buildValidator(Validator $validator)
    {
        return $validator;
    }

    protected function _execute(array $data)
    {
        return true;
    }
}
