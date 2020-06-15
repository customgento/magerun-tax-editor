<?php

declare(strict_types=1);

namespace CustomGento\TaxRatesEditor;

use N98\Magento\Command\AbstractMagentoCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EditCommand extends AbstractMagentoCommand
{
    protected function configure()
    {
        $this
            ->setName('taxrates:edit')
            ->setDescription('Edit an existing tax rate.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('TODO implement');
    }
}
