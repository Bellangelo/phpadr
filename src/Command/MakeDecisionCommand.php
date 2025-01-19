<?php

namespace ADR\Command;

use ADR\Domain\DecisionContent;
use ADR\Domain\DecisionRecord;
use ADR\Domain\Sequence;
use ADR\Filesystem\AutoDiscoverConfig;
use ADR\Filesystem\Workspace;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to make ADRs
 * 
 * @author José Carlos <josecarlos@globtec.com.br>
 */
class MakeDecisionCommand extends Command
{
    /**
     * Configures the command
     */
    protected function configure()
    {
        $options = [
            DecisionContent::STATUS_PROPOSED,
            DecisionContent::STATUS_ACCEPTED,
            DecisionContent::STATUS_REJECTED,
            DecisionContent::STATUS_DEPRECATED,
        ];
        
        $this
            ->setName('make:decision')
            ->setDescription('Creates a new ADR')
            ->setHelp('This command allows you to create a new ADR')
            ->addArgument(
                'title',
                InputArgument::REQUIRED,
                'The title of the ADR'
            )
            ->addArgument(
                'status',
                InputArgument::OPTIONAL,
                sprintf(
                   'The status of the ADR, available options: [%s]',
                   implode(', ', $options)
                ),
                DecisionContent::STATUS_ACCEPTED
            )
            ->addOption(
                'config',
                null,
                InputOption::VALUE_REQUIRED,
                'Config file'
            );
    }

    /**
     * Execute the command
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $discoverConfig = new AutoDiscoverConfig();
        $config = $discoverConfig->getConfig((string) $input->getOption('config'));
        $workspace = new Workspace($config->directory());
        $sequence = new Sequence($workspace);
        $content = new DecisionContent($sequence->next(), $input->getArgument('title'), $input->getArgument('status'));
        $record = new DecisionRecord($content, $config);
        
        $workspace->add($record);
        
        $output->writeln('<info>ADR created successfully</info>');

        return 0;
    }
}