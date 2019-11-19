<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\User;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class ResetPasswordCommand extends Command
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Users');
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        if ($args->hasArgument('email')) {
            /** @var \App\Model\Entity\User $user */
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

        return 1;
    }

    /**
     * Reset
     *
     * @param \App\Model\Entity\User $user User
     * @param \Cake\Console\ConsoleIo $io Io
     * @return void
     */
    private function reset(User $user, ConsoleIo $io): void
    {
        $hasher = new DefaultPasswordHasher();
        $io->out("Resetting password for " . $user->email);
        $user->password = $hasher->hash(strtolower($user->name ?? ""));
        $this->Users->save($user);
        $io->out("New password is " . strtolower($user->name ?? ""));
    }
}
