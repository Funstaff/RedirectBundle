<?php

namespace Funstaff\Bundle\RedirectBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * RedirectImportCommand.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('funstaff:redirect:import')
            ->setDescription('Import redirect data')
            ->setDefinition(array(
                new InputArgument('filename', InputArgument::OPTIONAL, 'filename', 'redirect.csv')))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $dialog = $this->getHelperSet()->get('dialog');

        $exportPath = sprintf(
            '%s'.DIRECTORY_SEPARATOR.'%s',
            $container->getParameter('funstaff_redirect.export_path'),
            $input->getArgument('filename')
        );

        $container
            ->get('funstaff_redirect.redirect_manager')
            ->import($exportPath);

        $output->writeln(sprintf(
            'Import data to "<comment>%s</comment>"',
            $exportPath
        ));
    }
}