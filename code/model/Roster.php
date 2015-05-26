<?php
/**
 * Author: danae-miller-clendon
 * Date: 26/05/15
 * Time: 11:22 AM
 */

/**
 * Class Roster
 *
 * @property string StartDate
 *
 * @TODO: Set title to be start date to end date
 * @TODO: Set up data storage for roster
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
     * Number of days from start date to display in roster. Max 7
     *
     * @var int
     */
    private static $number_of_days = 5;

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        /** =========================================
         * Requirements
        ==========================================*/

        Requirements::css('roster/css/rosterAdmin.css');

        $fields = parent::getCMSFields();

        /** =========================================
         * Date
         ==========================================*/

        $fields->addFieldsToTab('Root.Main', array(
            $dateField = DateField::create('StartDate')
        ));

        /** @var $dateField DateField */
        $dateField->setConfig('showcalendar', true);

        /** =========================================
         * Staff Roster
        ===========================================*/

        /** -----------------------------------------
         * Variables
        -------------------------------------------*/

        $roles        = JobRole::get();
        $numberOfDays = Config::inst()->get('Roster', 'number_of_days') ?: 5;

        /** @var DataList $staffMembers */
        $staffMembers = Group::get()->filter(array('Code' => 'staff-members'))->first()->Members();
        $staffMap     = $staffMembers->count() ? $staffMembers->map('ID', 'Initials')->toArray() : array();

        /** -----------------------------------------
         * Fields
        -------------------------------------------*/

        if ($roles->count()) {

            $fields->addFieldsToTab('Root.Main', array(
                LiteralField::create('', '<div id="roster-field-wrap">')
            ));

            // loop through the roles
            foreach ($roles->getIterator() as $role) {
                $fields->addFieldsToTab('Root.Main', array(
                    LiteralField::create('', sprintf('<div class="roster-row"><p><strong>%s</strong>: ', $role->Title))
                ));

                for ($i = 0; $i < $numberOfDays; $i++) {

                    $fields->addFieldsToTab('Root.Main', array(
                        ListboxField::create("am_{$role->ID}_$i", 'AM', $staffMap)->setMultiple(true)->addExtraClass('listbox-am'),
                        ListboxField::create("pm_{$role->ID}_$i", 'PM', $staffMap)->setMultiple(true)->addExtraClass('listbox-pm'),
                    ));

                }

                $fields->addFieldsToTab('Root.Main', array(
                    LiteralField::create('', '</p></div>')
                ));
            }

            $fields->addFieldsToTab('Root.Main', array(
                LiteralField::create('', '</div>')
            ));

        } else {
            // If no job roles exist, display a warning
            $fields->addFieldToTab('Root.Main', LiteralField::create('',
                sprintf(
                    '<div class="message warning"><p>%s</p></div>',
                    _t('Roster.NoRoleWarning', 'Can&apos;t create roster; no job roles exist. Add new roles under the "Job Roles" tab.')
                )
            ));
        }

        return $fields;
    }

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