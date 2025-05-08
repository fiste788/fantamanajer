<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\Team;
use App\Traits\CurrentMatchdayTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @property \App\Model\Table\ChampionshipsTable $Championships
 * @property \App\Model\Table\TeamsTable $Teams
 */
class RenewChampionshipCommand extends Command
{
    use CurrentMatchdayTrait;

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Renew the given championship');
        $parser->addArgument('id', ['help' => 'Championship id to renew', 'required' => true]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return int|null The exit code or null for success
     * @throws \Cake\Core\Exception\CakeException
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        /** @var \App\Model\Table\TeamsTable $teamsTable */
        $teamsTable = $this->fetchTable('Teams');
        $teamsTable->removeBehavior('Upload');
        /** @var \App\Model\Table\ChampionshipsTable $championshipsTable */
        $championshipsTable = $this->fetchTable('Championships');
        $relations = [
            'Teams' => [
                'PushNotificationSubscriptions',
                'EmailNotificationSubscriptions',
            ],
        ];

        $championship = $championshipsTable->get($args->getArgument('id'), contain: $relations);

        $newChampionship = $championshipsTable->newEntity(
            $championship->getOriginalValues(),
            ['accessibleFields' => ['*' => true]],
        );
        unset($newChampionship->id);
        $newChampionship->season_id = $this->currentSeason->id;
        $newChampionship->started = false;

        $newChampionship->teams = array_map(function (Team $team) use ($newChampionship, $teamsTable): Team {
            $newTeam = $teamsTable->newEntity(
                $team->getOriginalValues(),
                ['accessibleFields' => ['*' => true]],
            );
            unset($newTeam->id);
            $newTeam->championship_id = $newChampionship->id;
            $newTeam->email_notification_subscriptions = $team->email_notification_subscriptions;
            $newTeam->push_notification_subscriptions = $team->push_notification_subscriptions;

            return $newTeam;
        }, $championship->teams);
        $io->out('Save championship');
        //$io->abort(print_r($newChampionship, true));

        $championshipsTable->save($newChampionship, ['associated' => $relations]);
        $filesystem = new Filesystem();

        foreach ($championship->teams as $key => $team) {
            $newTeam = $newChampionship->teams[$key];
            $dir = $team->photo_dir ?? '';
            $photo = $team->photo ?? '';
            $filepath = ROOT . DS . $dir . $photo;
            $io->out('Cerco immagine in ' . $filepath);
            if ($filesystem->exists($filepath)) {
                $source = ROOT . DS . $dir;
                $io->out('Trovata immagine ' . $photo);

                $to = IMG_TEAMS . $newTeam->id . DS . 'photo' . DS;
                $io->out('Copy to ' . $source . ' to ' . $to);
                $filesystem->mirror($source, $to, null, ['overrride' => true, 'copy_on_windows' => true]);
                if ($team->photo != null) {
                    $finder = new Finder();
                    $finder->name($team->photo)->in($to);

                    foreach ($finder->getIterator() as $file) {
                        $newFileName = $newTeam->id . '.' . $file->getExtension();
                        $newTeam->photo = $newFileName;
                        $io->out('Rename file ' . $file->getPathname() . ' to ' . $newFileName);
                        $filesystem->rename(
                            $file->getPathname(),
                            $file->getPath() . DS . $newFileName,
                            true,
                        );
                    }

                    $newTeam->photo_dir = 'webroot' . DS . 'img' . DS . strtolower($newTeam->getSource()) . DS .
                        $newTeam->id . DS . 'photo' . DS;
                    $teamsTable->save($newTeam);
                }
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
