<?php

namespace App\Command;

use App\Service\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:delete-unverified-user',
    description: 'Add a short description for your command',
)]
class DeleteUnverifiedUserCommand extends Command
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to delete unverified users...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $start = $this->userService->getTime();
        $result = $this->userService->deleteUnverifiedUsers();
        $end = $this->userService->getTime();
        $io->success(
            sprintf('%d/%d user have been removed, Time usage : %s, Memory usage : %sM',
                $result['removed'],
                $result['total'],
                $this->userService->getDiffTime($start, $end),
                $this->userService->getMemoryUsage()
            )
        );

        return Command::SUCCESS;
    }
}
