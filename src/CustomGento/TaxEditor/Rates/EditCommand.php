<?php

declare(strict_types=1);

namespace CustomGento\TaxEditor\Rates;

use Exception;
use Mage;
use Mage_Tax_Model_Calculation_Rate;
use Mage_Tax_Model_Calculation_Rate_Title;
use N98\Magento\Command\AbstractMagentoCommand;
use N98\Util\BinaryString;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EditCommand extends AbstractMagentoCommand
{
    private const OPTION_UPDATE_TITLES = 'update-titles';
    private const OPTION_IDS = 'ids';
    private const OPTION_RATE = 'rate';

    protected function configure(): void
    {
        $this
            ->setName('tax:rates:edit')
            ->setDescription('Updates the rate of existing tax rates.')
            ->addOption(
                self::OPTION_IDS,
                null,
                InputOption::VALUE_REQUIRED,
                'Comma-separated list of tax rate IDs.'
            )
            ->addOption(
                self::OPTION_RATE,
                null,
                InputOption::VALUE_REQUIRED,
                'The new rate.'
            )
            ->addOption(
                self::OPTION_UPDATE_TITLES,
                null,
                InputOption::VALUE_NONE,
                'Update the code and the titles of the tax rate as well.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->detectMagento($output);
        if (!$this->initMagento()) {
            return 1;
        }

        $taxRateIds   = BinaryString::trimExplodeEmpty(',', $input->getOption(self::OPTION_IDS));
        $newRate      = (float)$input->getOption(self::OPTION_RATE);
        $updateTitles = $input->getOption(self::OPTION_UPDATE_TITLES);

        foreach ($taxRateIds as $taxRateId) {
            /** @var Mage_Tax_Model_Calculation_Rate $taxRate */
            $taxRate = Mage::getModel('tax/calculation_rate')->load($taxRateId);
            $oldRate = $taxRate->getRate();
            $success = $this->editTaxRate($output, $taxRate, $newRate, $updateTitles);
            if ($success) {
                $message = '<info>Updated tax rate with ID %d from %f to %f.</info>';
                $output->writeln(sprintf($message, $taxRateId, $oldRate, $newRate));
            }
        }

        return 0;
    }

    protected function editTaxRate(
        OutputInterface $output,
        Mage_Tax_Model_Calculation_Rate $taxRate,
        float $newRate,
        bool $updateTitles = false
    ): bool {
        if (!$taxRate->getId()) {
            $warning = '<warning>A tax rate with the ID %d does not exist.</warning>';
            $output->writeln(sprintf($warning, [$taxRate->getId()]));

            return false;
        }

        if ($updateTitles) {
            $this->updateTitlesForTaxRate($taxRate, (int)$newRate);
        }
        $taxRate->setRate($newRate);

        try {
            $taxRate->save();
        } catch (Exception $e) {
            $error = '<error>The tax rate with the ID %d could not be saved.</error>';
            $output->writeln(sprintf($error, [$taxRate->getId()]));

            return false;
        }

        return true;
    }

    protected function updateTitlesForTaxRate(Mage_Tax_Model_Calculation_Rate $taxRate, int $newRate): void
    {
        // assumption: everyone writes the tax rate without decimal places in their code / title
        $oldRate = (int)$taxRate->getRate();
        $newCode = str_replace($oldRate, $newRate, $taxRate->getCode());
        $taxRate->setCode($newCode);

        $titlesArray = [];
        /** @var Mage_Tax_Model_Calculation_Rate_Title $title */
        foreach ($taxRate->getTitles() as $title) {
            $newValue                          = str_replace($oldRate, $newRate, $title->getValue());
            $titlesArray[$title->getStoreId()] = $newValue;
        }
        $taxRate->setData('title', $titlesArray);
    }
}
