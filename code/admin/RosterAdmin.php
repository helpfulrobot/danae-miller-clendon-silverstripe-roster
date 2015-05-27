<?php
/**
 * Author: danae-miller-clendon
 * Date: 26/05/15
 * Time: 12:05 PM
 */

/**
 * Class RosterAdmin
 *
 * @TODO: Add GridFieldOrderableRows to the gridfield (JobRole)
 */
class RosterAdmin extends ModelAdmin
{
    private static $url_segment = 'roster';
    private static $menu_title = 'Roster';
    private static $menu_icon = 'roster/images/icons/roster.png';

    private static $managed_models = array(
        'Roster'  => array(
            'title' => 'Rosters'
        ),
        'JobRole' => array(
            'title' => 'Job Roles'
        )
    );
}