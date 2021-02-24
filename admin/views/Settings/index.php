<?php

use Nails\GeoIp\Constants;

//  Get any additional libraries we'll need
$oInput     = \Nails\Factory::service('Input');
$sActiveTab = $oInput->post('active_tab') ?: 'tab-drivers';

?>
<div class="group-eo-ip settings">
    <?=form_open()?>
    <input type="hidden" name="active_tab" value="<?=$sActiveTab?>" id="active-tab">
    <ul class="tabs" data-active-tab-input="#active-tab">
        <?php
        if (userHasPermission('admin:geoip:settings:drivers')) {
            ?>
            <li class="tab">
                <a href="#" data-tab="tab-drivers">Drivers</a>
            </li>
            <?php
        }
        ?>
    </ul>
    <section class="tabs">
        <?php
        if (userHasPermission('admin:geoip:settings:drivers')) {
            ?>
            <div class="tab-page tab-drivers">
                <?=adminHelper(
                    'loadSettingsDriverTable',
                    'Driver',
                    Constants::MODULE_SLUG
                )?>
            </div>
            <?php
        }
        ?>
    </section>
    <p>
        <?=form_submit('submit', lang('action_save_changes'), 'class="btn btn-primary"')?>
    </p>
    <?=form_close()?>
</div>
