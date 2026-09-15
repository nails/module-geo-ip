<?php

namespace Nails\GeoIp\Housekeeping;

use Nails\Common\Service\Database;
use Nails\Factory;
use Nails\GeoIp\Service\GeoIp;
use Nails\Housekeeping\Routine\Base;
use Nails\Housekeeping\Routine\Context;
use Nails\Housekeeping\Routine\Result;

class Cache extends Base
{
    const LABEL           = 'Geo-IP cache';
    const DESCRIPTION     = 'Deletes Geo-IP cache rows older than CACHE_PERIOD';
    const CRON_EXPRESSION = '@hourly';

    public function execute(Context $oContext): Result
    {
        /** @var Database $oDb */
        $oDb        = Factory::service('Database');
        $sTable     = GeoIp::DB_CACHE_TABLE;
        $sPeriod    = GeoIp::CACHE_PERIOD;
        $iBatchSize = 200;
        $iProcessed = 0;
        $iLastId    = 0;

        $oContext
            ->writeln(sprintf(
                'Deleting from <comment>%s</comment> older than <comment>%s</comment>',
                $sTable,
                $sPeriod
            ))
            ->log(sprintf(
                'TABLE %s period=%s batch_size=%d dry_run=%s',
                $sTable,
                $sPeriod,
                $iBatchSize,
                $oContext->isDryRun() ? 'true' : 'false'
            ));

        while (true) {
            $oDb->select('id, ip, created');
            $oDb->where('created <', 'DATE_SUB(NOW(), INTERVAL ' . $sPeriod . ')', false);
            $oDb->where('id >', $iLastId);
            $oDb->order_by('id', 'asc');
            $oDb->limit($iBatchSize);
            $aRows = $oDb->get($sTable)->result();

            if (empty($aRows)) {
                break;
            }

            $aIds = [];
            foreach ($aRows as $oRow) {
                $iId     = (int) $oRow->id;
                $iLastId = $iId;
                $aIds[]  = $iId;
                $sAudit  = sprintf(
                    'id=%d ip=%s created=%s',
                    $iId,
                    (string) $oRow->ip,
                    (string) $oRow->created
                );
                $oContext
                    ->log('DELETE ' . $sAudit)
                    ->writeln(' ↳ ' . $sAudit);
            }

            if (!$oContext->isDryRun()) {
                $oDb->where_in('id', $aIds);
                $oDb->delete($sTable);
            }

            $iProcessed += count($aIds);
        }

        $oContext->writeln(sprintf(
            '<comment>%s</comment> %s',
            number_format($iProcessed),
            $oContext->isDryRun() ? 'would be deleted' : 'deleted'
        ));

        return Result::ok($iProcessed);
    }
}
