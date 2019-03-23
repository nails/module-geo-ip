<?php

/**
 * This class registers some handlers for GeoIp settings
 *
 * @package     Nails
 * @subpackage  module-geo-ip
 * @category    AdminController
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\Admin\GeoIp;

use Nails\Admin\Controller\Base;
use Nails\Admin\Helper;
use Nails\Common\Exception\ValidationException;
use Nails\Factory;

class Settings extends Base
{
    /**
     * Announces this controller's navGroups
     * @return stdClass
     */
    public static function announce()
    {
        $oNavGroup = Factory::factory('Nav', 'nails/module-admin');
        $oNavGroup->setLabel('Settings');
        $oNavGroup->setIcon('fa-wrench');

        if (userHasPermission('admin:geoip:settings:*')) {
            $oNavGroup->addAction('Geo-IP');
        }

        return $oNavGroup;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns an array of permissions which can be configured for the user
     * @return array
     */
    public static function permissions(): array
    {
        $permissions           = parent::permissions();
        $permissions['driver'] = 'Can update driver settings';

        return $permissions;
    }

    // --------------------------------------------------------------------------

    /**
     * Manage Email settings
     * @return void
     */
    public function index()
    {
        if (!userHasPermission('admin:geoip:settings:*')) {
            unauthorised();
        }

        $oDb            = Factory::service('Database');
        $oInput         = Factory::service('Input');
        $oDriverService = Factory::service('Driver', 'nails/module-geo-ip');

        //  Process POST
        if ($oInput->post()) {

            $oDb->trans_begin();

            try {

                $sKeyDriver = $oDriverService->getSettingKey();

                $oFormValidation = Factory::service('FormValidation');
                $oFormValidation->set_rules($sKeyDriver, '', 'required');
                if (!$oFormValidation->run()) {
                    throw new ValidationException(lang('fv_there_were_errors'));
                }

                $oDriverService->saveEnabled($oInput->post($sKeyDriver));

                $oDb->trans_commit();

                $this->data['success'] = 'Geo-IP settings were saved.';

            } catch (\Exception $e) {
                $oDb->trans_rollback();
                $this->data['error'] = 'There was a problem saving settings. ' . $e->getMessage();
            }
        }

        // --------------------------------------------------------------------------

        //  Get data
        $this->data['settings']        = appSetting(null, 'nails/module-geo-ip', true);
        $this->data['drivers']         = $oDriverService->getAll();
        $this->data['drivers_enabled'] = $oDriverService->getEnabledSlug();

        Helper::loadView('index');
    }
}
