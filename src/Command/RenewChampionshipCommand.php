<?php

namespace App\Command;

use App\Model\Entity\Season;
use App\Model\Entity\Team;
use App\Traits\CurrentMatchdayTrait;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;

/**
 * @property \App\Model\Table\ChampionshipsTable $Championships
 */
class RenewChampionshipCommand extends Command
{
    use CurrentMatchdayTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Championships');
        $this->getCurrentMatchday();
    }

    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser->setDescription('Renew the given championship');
        $parser->addArgument('id', ['help' => 'Championship id to renew']);

        return $parser;
    }

    /**
     *
     * @return Season
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->Championships->Teams->removeBehavior('Upload');
        $championship = $this->Championships->get($args->getArgument('id'), ['contain' => 'Teams.NotificationSubscriptions']);
        $newChampionship = $this->Championships->newEntity($championship->getOriginalValues());
        unset($newChampionship->id);
        $newChampionship->season_id = $this->currentSeason->id;
        $newChampionship->started = false;
        $that = $this;
        $newChampionship->teams = array_map(function (Team $team) use ($newChampionship, $that) {
            $newTeam = $that->Championships->Teams->newEntity($team->getOriginalValues());
            unset($newTeam->id);
            $newTeam->championship_id = $newChampionship->id;

            return $newTeam;
        }, $championship->teams);
        $io->out('Save championship');
        $this->Championships->save($newChampionship);
        foreach ($championship->teams as $key => $team) {
            $newTeam = $newChampionship->teams[$key];
            $file = new File(ROOT . DS . $team->photo_dir . $team->photo);
            $io->out('Cerco immagine in ' . ROOT . DS . $team->photo_dir . $team->photo);
            if ($file->exists()) {
                $io->out('Trovata immagine ' . $team->photo);
                $folder = new Folder(ROOT . DS . $team->photo_dir);
                $to = FILES . $newTeam->getSource() . DS . $newTeam->id . DS . 'photo';
                if ($folder->copy(['to' => $to])) {
                    $io->out('Copiata folder ' . $to);
                    $newFolder = new Folder($to);
                    $files = $newFolder->findRecursive($team->photo);
                    foreach ($files as $file) {
                        $file = new File($file);
                        if ($file->copy($file->Folder->path . DS . $newTeam->id . "." . $file->ext())) {
                            $file->delete();
                        }
                    }
                    $newTeam->photo = $newTeam->id . "." . $file->ext();
                    $newTeam->photo_dir = 'webroot' . DS . 'files' . DS . $newTeam->getSource() . DS . $newTeam->id . DS . 'photo' . DS;
                    $this->Championships->Teams->save($newTeam);
                }
            }
        }
    }
}
