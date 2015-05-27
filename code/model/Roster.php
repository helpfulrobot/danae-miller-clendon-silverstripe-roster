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
 * @method ManyManyList WeeklyRosters
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

    private static $many_many = array(
        'WeeklyRosters' => 'JobRole'
    );

    private static $many_many_extraFields = array(
        'WeeklyRosters' => array(
            'StaffAm0' => 'Varchar(20)',
            'StaffPm0' => 'Varchar(20)',
            'StaffAm1' => 'Varchar(20)',
            'StaffPm1' => 'Varchar(20)',
            'StaffAm2' => 'Varchar(20)',
            'StaffPm2' => 'Varchar(20)',
            'StaffAm3' => 'Varchar(20)',
            'StaffPm3' => 'Varchar(20)',
            'StaffAm4' => 'Varchar(20)',
            'StaffPm4' => 'Varchar(20)',
            'StaffAm5' => 'Varchar(20)',
            'StaffPm5' => 'Varchar(20)',
            'StaffAm6' => 'Varchar(20)',
            'StaffPm6' => 'Varchar(20)'
        )
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

        $fields->removeByName(array('WeeklyRosters'));

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

            if ($this->WeeklyRosters()->count()) {

                $editableColumns = new GridFieldEditableColumns();
                $editableColumns->setDisplayFields(array(
                    'Title' => array(
                        'title' => 'Role',
                        'field' => 'ReadonlyField'
                    ),
                    'StaffAm0' => function($record, $column, $grid) use ($staffMap) {
                        return ListboxField::create($column, 'AM', $staffMap)->setMultiple(true);
                    },
                    'StaffPm0' => function($record, $column, $grid) use ($staffMap) {
                        return ListboxField::create($column, 'PM', $staffMap)->setMultiple(true);
                    },
                    'StaffAm1' => function($record, $column, $grid) use ($staffMap) {
                        return ListboxField::create($column, 'AM', $staffMap)->setMultiple(true);
                    },
                    'StaffPm1' => function($record, $column, $grid) use ($staffMap) {
                        return ListboxField::create($column, 'PM', $staffMap)->setMultiple(true);
                    },
                    'StaffAm2' => function($record, $column, $grid) use ($staffMap) {
                        return ListboxField::create($column, 'AM', $staffMap)->setMultiple(true);
                    },
                    'StaffPm2' => function($record, $column, $grid) use ($staffMap) {
                        return ListboxField::create($column, 'PM', $staffMap)->setMultiple(true);
                    },
                    'StaffAm3' => function($record, $column, $grid) use ($staffMap) {
                        return ListboxField::create($column, 'AM', $staffMap)->setMultiple(true);
                    },
                    'StaffPm3' => function($record, $column, $grid) use ($staffMap) {
                        return ListboxField::create($column, 'PM', $staffMap)->setMultiple(true);
                    },
                    'StaffAm4' => function($record, $column, $grid) use ($staffMap) {
                        return ListboxField::create($column, 'AM', $staffMap)->setMultiple(true);
                    },
                    'StaffPm4' => function($record, $column, $grid) use ($staffMap) {
                        return ListboxField::create($column, 'PM', $staffMap)->setMultiple(true);
                    }
                ));

                // Adjust the WeeklyRoster gridfield
                $grid = new GridField(
                    'WeeklyRosters',
                    'Weekly Roster for ' . $this->dbObject('StartDate')->Nice(),
                    $this->WeeklyRosters(),
                    GridFieldConfig::create()
                        ->addComponent(new GridFieldButtonRow('before'))
                        ->addComponent(new GridFieldToolbarHeader())
                        ->addComponent(new RosterGridFieldTitleHeader())
                        ->addComponent($editableColumns)
                );

                $fields->addFieldToTab('Root.Main', $grid);

            } else {
                $this->WeeklyRosters()->addMany($roles);
            }

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

    private function makeDisplayFieldArray($staffMap, $numberOfDays)
    {
        $weeklyRosters = array();

        $weeklyRosters['Title'] = array(
            'title' => 'Role',
            'field' => 'ReadonlyField'
        );

        for ($i = 0; $i < $numberOfDays; $i++) {

            $weeklyRosters["ManyMany[StaffAm{$i}]"] = function ($record, $column, $grid) use ($staffMap, $i) {
                return ListboxField::create("ManyMany[StaffAm{$i}]", 'AM', $staffMap)->setMultiple(true);
            };

            $weeklyRosters["ManyMany[StaffPm{$i}]"] = function ($record, $column, $grid) use ($staffMap, $i) {
                return ListboxField::create("ManyMany[StaffAm{$i}]", 'PM', $staffMap)->setMultiple(true);
            };

        }

        return $weeklyRosters;
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