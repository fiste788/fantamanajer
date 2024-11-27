<?php

declare(strict_types=1);

namespace App\Command;

use AllowDynamicProperties;
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
            $tmpFile = new SplFileInfo(ROOT . DS . $team->photo_dir . $team->photo);

            $newFile = new SplFileInfo($tmpFile->getPath() . DS . $tmpFile->getBasename($tmpFile->getExtension()) . "webp");
            if ($tmpFile->isFile()) {
                $io->info($newFile->__toString());
                Image::useImageDriver(ImageDriver::Gd)->load($tmpFile->getPathname())->quality(80)->save($newFile->getPathname());
                $team->photo = $newFile->getFilename();
                $team->photo_size = $newFile->getSize();
                $team->photo_type = $newFile->getType();

                $finder = new Finder();
                $finder->in($tmpFile->getPath())->name(["*.jpg", "*.jpeg", "*.png"]);

                foreach ($finder->getIterator() as $file) {
                    $io->info($file->__toString());
                    $newFile = new SplFileInfo($file->getPath() . DS . $file->getBasename($file->getExtension()) . "webp");
                    Image::useImageDriver(ImageDriver::Gd)->load($file->getPathname())->quality(80)->save($newFile->getPathname());
                }
                $teamsTable->save($team);
            }
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
