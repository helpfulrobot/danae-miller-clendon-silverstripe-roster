<?php
/**
 * Author: danae-miller-clendon
 * Date: 26/05/15
 * Time: 11:22 AM
 */

/**
 * Class Roster
 */
class Roster extends DataObject implements PermissionProvider
{
    private static $singular_name = 'Roster';
    private static $plural_name = 'Rosters';
    private static $description = 'Represents a working week on the roster';
    private static $default_sort = 'StartDate DESC';

    private static $db = array(
        'StartDate' => 'Date'
    );

    private static $summary_fields = array(
        'StartDate'
    );

    /**
     * Creates default Staff Member group
     */
    public function requireDefaultRecords()
    {
        $staffGroup = \Group::get()->filter(array('Code' => 'staff-members'));
        if (!$staffGroup->count()) {
            /** @var \Group $staffGroup */
            $staffGroup = \Group::create(
                array(
                    'Title' => _t('Roster.DefaultGroupTitleStaffMembers', 'Staff Members'),
                    'Code'  => 'staff-members'
                )
            );

            $staffGroup->write();
            \DB::alteration_message(_t('Roster.GroupCreated', 'Staff Members group created'), 'created');
        }
    }

    /**
     * @return array
     */
    public function providePermissions()
    {
        return array(
            'MODIFY_ROSTER' => array(
                'category' => _t('Roster.RosterPermissions', 'Roster Permissions'),
                'name'     => _t('Roster.ModifyRoster', 'Modify Roster'),
                'help'     => _t('Roster.ModifyRosterHelp', 'Allow the user to do all kinds of special things')
            )
        );
    }
}