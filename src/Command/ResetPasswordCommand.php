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
        $usersTable = $this->fetchTable('Users');
        if ($args->hasArgument('email')) {

            /** @var \App\Model\Entity\User|null $user */
            $user = $usersTable->find()->where(['email' => $args->getArgument('email')])->first();
            if ($user != null) {
                $this->reset($user, $io);
            }
        } else {
            /** @var array<\App\Model\Entity\User> $users */
            $users = $usersTable->find()->all();
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
     * @throws \Cake\Core\Exception\CakeException
     */
    private function reset(User $user, ConsoleIo $io): void
    {
        $usersTable = $this->fetchTable('Users');
        if ($user->email != null && $user->name != null) {
            $hasher = new DefaultPasswordHasher();
            $io->out('Resetting password for ' . $user->email);
            $user->password = $hasher->hash(strtolower($user->name));
            $usersTable->save($user);
            $io->out('New password is ' . strtolower($user->name));
        }
    }
}
