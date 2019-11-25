<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Entity\Team;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Arguments;
use Cake\Console\Command;
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
     * @inheritDoc
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
     *
     * @return int|null
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
        $newChampionship = $this->Championships->newEntity($championship->getOriginalValues());
        unset($newChampionship->id);
        $newChampionship->season_id = $this->currentSeason->id;
        $newChampionship->started = false;

        /** @var \App\Command\RenewChampionshipCommand $that */
        $that = $this;
        $newChampionship->teams = array_map(function (Team $team) use ($newChampionship, $that): Team {
            $newTeam = $that->Teams->newEntity($team->getOriginalValues());
            unset($newTeam->id);
            $newTeam->championship_id = $newChampionship->id;

            return $newTeam;
        }, $championship->teams);
        $io->out('Save championship');

        $this->Championships->save($newChampionship);
        $filesystem = new Filesystem();
        foreach ($championship->teams as $key => $team) {
            $newTeam = $newChampionship->teams[$key];
            $filepath = ROOT . DS . ($team->photo_dir ?? '') . ($team->photo ?? '');
            $io->out('Cerco immagine in ' . $filepath);
            if ($filesystem->exists($filepath)) {
                $source = ROOT . DS . ($team->photo_dir ?? '');
                $io->out('Trovata immagine ' . ($team->photo ?? ''));

                $to = WWW_ROOT . $newTeam->getSource() . DS . $newTeam->id . DS . 'photo';
                if ($filesystem->mirror($source, $to, null, ['overrride' => true, 'copy_on_windows' => true])) {
                    $io->out('Copiata folder ' . $to);
                    if ($team->photo != null) {
                        $finder = new Finder();
                        $finder->name($team->photo)->in($to);

                        foreach ($finder->getIterator() as $file) {
                            $newFileName = $newTeam->id . "." . $file->getExtension();
                            $newTeam->photo = $newFileName;
                            $filesystem->rename(
                                $file->getRelativePathname(),
                                $file->getRelativePath() . DS . $newFileName,
                                true
                            );
                        }

                        $newTeam->photo_dir = 'webroot' . DS . 'files' . DS . $newTeam->getSource() . DS .
                            $newTeam->id . DS . 'photo' . DS;
                        $this->Championships->Teams->save($newTeam);
                    }
                }
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
