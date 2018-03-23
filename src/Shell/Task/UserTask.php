<?php
namespace App\Shell\Task;

use Cake\Console\Shell;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UserTask extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
    }

    public function main()
    {
    }

    public function startup()
    {
        parent::startup();
        if ($this->param('no-interaction')) {
            $this->interactive = false;
        }
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand(
            'reset_password',
            [
            'help' => 'Reset password using name'
            ]
        );
        $parser->addOption('no-interaction', [
            'short' => 'n',
            'help' => 'Disable interaction',
            'boolean' => true,
            'default' => false
        ]);

        return $parser;
    }

    public function resetPassword($email = null)
    {
        if ($email) {
            $user = $this->Users->find()->where(['email' => $email])->first();
            if ($user != null) {
                $this->reset($user);
            }
        } else {
            $users = $this->Users->find()->all();
            //$hasher =  new DefaultPasswordHasher();
            foreach ($users as $user) {
                $this->reset($user);
            }
        }
    }

    private function reset(\App\Model\Entity\User $user)
    {
        $this->out("Resetting password for " . $user->email);
        $user->password = strtolower($user->name);
        $this->Users->save($user);
        $this->out("New password is " . strtolower($user->name));
    }
}
