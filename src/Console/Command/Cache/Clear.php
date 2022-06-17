<?php

namespace Nails\GeoIp\Console\Command\Cache;

use Nails\Console\Command\Base;
use Nails\Factory;
use Nails\GeoIp\Constants;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Clear extends Base
{
    /**
     * Configures the command
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('geoip:cache:clear')
            ->setDescription('Clears expired items from the Geo-IP cache')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Clear the entire cache');
    }

    // --------------------------------------------------------------------------

    /**
     * Executes the command
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $oInput
     * @param \Symfony\Component\Console\Output\OutputInterface $oOutput
     *
     * @return int
     * @throws \Nails\Common\Exception\FactoryException
     */
    protected function execute(InputInterface $oInput, OutputInterface $oOutput)
    {
        parent::execute($oInput, $oOutput);
        $this->banner('Geo-IP: Clear Cache');

        /** @var \Nails\GeoIp\Service\GeoIp $oService */
        $oService = Factory::service('GeoIp', Constants::MODULE_SLUG);
        /** @var \Nails\Common\Service\Database $oDb */
        $oDb = Factory::service('Database');

        try {

            $bForce = $oInput->getOption('force');

            if (!$bForce) {
                $oDb->where('created <', 'DATE_SUB(NOW(), INTERVAL ' . $oService::CACHE_PERIOD . ')', false);
            }
            $iTotal = $oDb->count_all_results($oService::DB_CACHE_TABLE);
            $oOutput->write(sprintf(
                'Purging <info>%s</info> records from the cache... ',
                $iTotal
            ));

            if (!$bForce) {
                $oDb->where('created <', 'DATE_SUB(NOW(), INTERVAL ' . $oService::CACHE_PERIOD . ')', false);
                $oDb->delete($oService::DB_CACHE_TABLE);
            } else {
                $oDb->truncate($oService::DB_CACHE_TABLE);
            }

            $oOutput->writeln('<info>done</info>');

        } catch (\Throwable $e) {
            $oOutput->writeln('<error>ERROR: ' . $e->getMessage() . '</error>');
        } finally {
            $oOutput->writeln('');
        }

        return static::EXIT_CODE_SUCCESS;
    }
}
