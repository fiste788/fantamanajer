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
     * @throws \Cake\Datasource\Exception\MissingModelException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Championships');
        $this->loadModel('Teams');
        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Renew the given championship');
        $parser->addArgument('id', ['help' => 'Championship id to renew']);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $this->Championships->Teams->removeBehavior('Upload');
        $championship = $this->Championships->get($args->getArgument('id'), ['contain' => [
            'Teams' => [
                'PushNotificationSubscriptions',
                'EmailNotificationSubscriptions',
            ],
        ]]);
        $newChampionship = $this->Championships->newEntity(
            $championship->getOriginalValues(),
            ['accessibleFields' => ['*' => true]]
        );
        unset($newChampionship->id);
        $newChampionship->season_id = $this->currentSeason->id;
        $newChampionship->started = false;

        $newChampionship->teams = array_map(function (Team $team) use ($newChampionship): Team {
            $newTeam = $this->Teams->newEntity(
                $team->getOriginalValues(),
                ['accessibleFields' => ['*' => true]]
            );
            unset($newTeam->id);
            $newTeam->championship_id = $newChampionship->id;

            return $newTeam;
        }, $championship->teams);
        $io->out('Save championship');

        $this->Championships->save($newChampionship);
        $filesystem = new Filesystem();
        foreach ($championship->teams as $key => $team) {
            $newTeam = $newChampionship->teams[$key];
            $dir = $team->photo_dir ?? '';
            $photo = $team->photo ?? '';
            $filepath = (string)ROOT . DS . $dir . $photo;
            $io->out('Cerco immagine in ' . $filepath);
            if ($filesystem->exists($filepath)) {
                $source = (string)ROOT . DS . $dir;
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
                            true
                        );
                    }

                    $newTeam->photo_dir = 'webroot' . DS . 'img' . DS . strtolower($newTeam->getSource()) . DS .
                        $newTeam->id . DS . 'photo' . DS;
                    $this->Championships->Teams->save($newTeam);
                }
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
