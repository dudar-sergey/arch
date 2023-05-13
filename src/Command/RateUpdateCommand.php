<?php

namespace App\Command;

use App\DataTransformer\CNBDataTransformer;
use App\Entity\Currency;
use App\Entity\Rate;
use App\Integration\CNB\HttpClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'rate:update',
    description: 'Add a short description for your command',
)]
class RateUpdateCommand extends Command
{
    public function __construct(private HttpClient $client, private EntityManagerInterface $em, private CNBDataTransformer $transformer, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $rates = $this->client->getRateByDate(new \DateTime('now'));

        $this->em->getRepository(Rate::class)->saveCollection($this->transformer->transform($rates));

        return Command::SUCCESS;
    }
}
