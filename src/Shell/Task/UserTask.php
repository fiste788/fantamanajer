<?php

namespace App\Shell\Task;

use App\Model\Table\UsersTable;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Console\Shell;

/**
 * @property UsersTable $Users
 */
class UserTask extends Shell {

    
    public function initialize() {
        parent::initialize();
        $this->loadModel('Users');
    }

    public function main() {
        
    }
    
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('reset_password', [
            'help' => 'Reset password using name'
        ]);
        return $parser;
    }
    
    public function resetPassword() {
        $users = $this->Users->find()->all();
        //$hasher =  new DefaultPasswordHasher();
        foreach($users as $user) {
            $this->out("Resetting password for " . $user->email);
            $user->password = strtolower($user->name);
            $this->Users->save($user);
            $this->out("New password is " . strtolower($user->name));
        }
    }

}
