<?php
declare(strict_types=1);

namespace App\Command;

use AllowDynamicProperties;
use App\Traits\CurrentMatchdayTrait;
use Burzum\CakeServiceLayer\Service\ServiceAwareTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\CommandInterface;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Override;

/**
 * @property \App\Service\ComputeScoreService $ComputeScore
 * @property \App\Service\RatingService $Rating
 * @property \App\Service\DownloadRatingsService $DownloadRatings
 * @property \App\Service\UpdateMemberService $UpdateMember
 * @property \App\Service\PushNotificationService $PushNotification
 * @property \Cake\ORM\Table $Points
 */
#[AllowDynamicProperties]
class UpdateMembersCommand extends Command
{
    use CurrentMatchdayTrait;
    use ServiceAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @throws \Cake\Core\Exception\CakeException
     * @throws \UnexpectedValueException
     * @throws \RuntimeException
     */
    #[Override]
    public function initialize(): void
    {
        parent::initialize();

        $this->getCurrentMatchday();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addArgument('matchday');

        $parser->setDescription('Update members');

        return $parser;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    #[Override]
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $this->loadService('DownloadRatings', [$io]);
        $this->loadService('UpdateMember', [$io]);

        $matchdayNumber = $args->hasArgument('matchday') ?
            (int)$args->getArgument('matchday') : $this->currentMatchday->number;

        /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
        $matchdaysTable = $this->fetchTable('Matchdays');
        /** @var \App\Model\Entity\Matchday|null $matchday */
        $matchday = $matchdaysTable->find()->contain(['Seasons'])->where([
            'number' => $matchdayNumber,
            'season_id' => $this->currentSeason->id,
            ])->first();

        if ($matchday === null) {
            $io->err('Matchday ' . $matchdayNumber . ' not found in current season');

            return CommandInterface::CODE_ERROR;
        }

        /** @var \App\Model\Table\MatchdaysTable $matchdaysTable */
        $io->out('Starting decript file matchday ' . $matchday->number);
        $path = $this->DownloadRatings->getRatings($matchday, 0, true);
        if ($path != null) {
            $io->out('Updating table players');
            $this->UpdateMember->updateMembers($matchday, $path);
        } else {
            $io->out('Cannot download ratings from gazzetta');
        }

        return CommandInterface::CODE_SUCCESS;
    }
}
