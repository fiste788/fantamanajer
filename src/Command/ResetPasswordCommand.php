<?php

namespace App\Command;

use App\Model\Entity\User;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class ResetPasswordCommand extends Command
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Users');
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        if ($args->hasArgument('email')) {
            $user = $this->Users->find()->where(['email' => $args->getArgument('email')])->first();
            if ($user != null) {
                $this->reset($user, $io);
            }
        } else {
            $users = $this->Users->find()->all();
            foreach ($users as $user) {
                $this->reset($user, $io);
            }
        }
    }

    private function reset(User $user, ConsoleIo $io)
    {
        $io->out("Resetting password for " . $user->email);
        $user->password = strtolower($user->name);
        $this->Users->save($user);
        $io->out("New password is " . strtolower($user->name));
    }
}
