<?php

declare(strict_types=1);

namespace App\Command;

use AllowDynamicProperties;
use App\Model\Entity\Team;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Spatie\Image\Enums\ImageDriver;
use Spatie\Image\Image;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

#[AllowDynamicProperties]
class ConvertTeamImageCommand extends Command
{

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
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Convert to webp');

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
        /** @var array<\App\Model\Entity\Team> $teams */
        $teams = $teamsTable->find()->all();

        foreach ($teams as $team) {
            if ($team->photo_dir) {
                $tmpDir = new SplFileInfo(ROOT . DS . $team->photo_dir);
                if ($tmpDir->isDir()) {
                    $tmpFile = new SplFileInfo(ROOT . DS . $team->photo_dir . $team->photo);


                    $finder = new Finder();
                    $finder->in($tmpFile->getPath())->directories();
                    foreach (iterator_to_array($finder->getIterator()) as $dir) {

                        (new Filesystem())->remove($dir->getPathname());
                    }

                    $finder = new Finder();
                    $finder->in($tmpFile->getPath())->exclude([$tmpFile->getBasename($tmpFile->getExtension()) . "*"]);
                    foreach ($finder->getIterator() as $file) {

                        if ($file->getFilenameWithoutExtension() != $tmpFile->getBasename("." . $tmpFile->getExtension()) || $file->getFilename() == $tmpFile->getFilename() . '.webp') {
                            $io->info($file->__toString());

                            unlink($file->getPathname());
                        }
                    }

                    $finder = new Finder();
                    $finder->in($tmpFile->getPath())->files();
                    $files = iterator_to_array($finder);

                    if (count($files) == 1) {
                        foreach ($finder->getIterator() as $file) {
                            $team->photo = $file->getFilename();
                            $teamsTable->save($team);
                        }
                    } else if (count($files) > 1) {
                        foreach ($finder->getIterator() as $file) {

                            if ($file->getExtension() != 'webp') {
                                $team->photo = $file->getFilename();
                                $teamsTable->save($team);
                            } else {
                                $io->info($file->__toString());

                                unlink($file->getPathname());
                            }
                        }
                    }

                    $finder = new Finder();
                    $finder->in($tmpFile->getPath())->files();
                    foreach ($finder->getIterator() as $file) {

                        $image = Image::load($file->getPathname());
                        foreach (Team::$size as $value) {
                            if ($value < $image->getWidth()) {

                                mkdir($tmpFile->getPath() . DS . $value . 'w');
                                $tmp = $tmpFile->getPath() . DS . $value . 'w' . DS . $file->getFilename();
                                $io->info($tmp);
                                $image->width($value)->optimize()->save($tmp);
                            }
                        }
                    }
                }
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
