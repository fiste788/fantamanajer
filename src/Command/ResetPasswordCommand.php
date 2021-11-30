<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\User;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class ResetPasswordCommand extends Command
{
    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Reset passwords');
        $parser->addArgument('email', ['help' => 'User email to reset']);

        return $parser;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        if ($args->hasArgument('email')) {
            /** @var \App\Model\Entity\User|null $user */
            $user = $this->Users->find()->where(['email' => $args->getArgument('email')])->first();
            if ($user != null) {
                $this->reset($user, $io);
            }
        } else {
            /** @var \App\Model\Entity\User[] $users */
            $users = $this->Users->find()->all();
            foreach ($users as $user) {
                $this->reset($user, $io);
            }
        }

        return CommandInterface::CODE_SUCCESS;
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
        if ($user->email && $user->name) {
            $hasher = new DefaultPasswordHasher();
            $io->out('Resetting password for ' . $user->email);
            $user->password = $hasher->hash(strtolower($user->name));
            $this->Users->save($user);
            $io->out('New password is ' . strtolower($user->name));
        }
    }
}
