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
 * @property string EndDate
 * @property string Holidays
 *
 * @method ManyManyList WeeklyRosters
 * @method ManyManyList StaffLeave
 *
 * @TODO: Implement number of days
 * @TODO: Improve storage efficiency
 */
class Roster extends DataObject implements PermissionProvider
{
    private static $singular_name = 'Roster';
    private static $plural_name = 'Rosters';
    private static $description = 'Represents a working week on the roster';
    private static $default_sort = 'StartDate DESC';

    private static $db = array(
        'StartDate' => 'Date',
        'EndDate'   => 'Date',
        'Holidays'  => 'Text'
    );

    private static $summary_fields = array(
        'StartDate'
    );

    private static $many_many = array(
        'WeeklyRosters' => 'JobRole',
        'StaffLeave'    => 'Member'
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
        ),
        'StaffLeave' => array(
            'Leave' => 'Text'
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
        ===========================================*/

        Requirements::css('roster/css/rosterAdmin.css');

        $fields    = parent::getCMSFields();
        $endDate   = $this->EndDate;
        $startDate = $this->StartDate;

        /** =========================================
         * Date
         ==========================================*/

        /** @var MultiDateField $holidayField */
        $holidayField = MultiDateField::create('Holidays');
        $holidayField->setConfig('dateformat', 'dd-MM-yyyy');
        $holidayField->setConfig('showcalendar', true);
        $holidayField->setConfig('separator',', ');
        $holidayField->setConfig('min', $startDate);
        $holidayField->setConfig('max', $endDate);

        /** @var DateField $dateField */
        $dateField = DateField::create('StartDate');
        $dateField->setConfig('dateformat', 'dd-MM-yyyy');

        $fields->addFieldsToTab('Root.Main', array(
            $dateField,
            $holidayField
        ));

        /** @var $dateField DateField */
        $dateField->setConfig('showcalendar', true);

        /** =========================================
         * Staff Roster
        ===========================================*/

        $fields->removeByName(array('WeeklyRosters', 'StaffLeave', 'EndDate'));

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

            /** -----------------------------------------
             * Weekly Rosters
            -------------------------------------------*/

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
                $grid = GridField::create(
                    'WeeklyRosters',
                    sprintf('Weekly Roster for %s - %s', $this->dbObject('StartDate')->Format('D jS M'), $this->dbObject('EndDate')->Format('D jS M')),
                    $this->WeeklyRosters(),
                    GridFieldConfig::create()
                        ->addComponent(new GridFieldToolbarHeader())
                        ->addComponent(new RosterGridFieldTitleHeader($this->dbObject('StartDate'), $this->getHolidayArray()))
                        ->addComponent($editableColumns)
                )->addExtraClass('roster-gridfield');

                $fields->addFieldToTab('Root.Main', $grid);

            } else {
                $fields->addFieldToTab('Root.Main', LiteralField::create('',
                    sprintf(
                        '<div class="message notice"><p>%s</p></div>',
                        _t('Roster.SaveFirstNotice', 'Choose a starting date, then press the green "Save" button at the bottom of the screen.')
                    )
                ));
                $this->WeeklyRosters()->addMany($roles);
            }

            /** -----------------------------------------
             * Staff Leave
            -------------------------------------------*/

            if ($this->StaffLeave()->count()) {

                $editableColumns = new GridFieldEditableColumns();
                $editableColumns->setDisplayFields(array(
                    'Title' => array(
                        'title' => 'Staff Member',
                        'field' => 'ReadonlyField'
                    ),
                    'Leave' => function($record, $column, $grid) use ($staffMap, $startDate, $endDate) {
                        return MultiDateField::create($column)
                            ->setConfig('dateformat', 'dd-MM-yyyy')
                            ->setConfig('showcalendar', true)
                            ->setConfig('separator',', ')
                            ->setConfig('min', $startDate)
                            ->setConfig('max', $endDate);
                    },
                ));

                $grid = GridField::create(
                    'StaffLeave',
                    sprintf('Staff Leave for %s - %s', $this->dbObject('StartDate')->Format('D jS M'), $this->dbObject('EndDate')->Format('D jS M')),
                    $this->StaffLeave(),
                    GridFieldConfig::create()
                        ->addComponent(new GridFieldToolbarHeader())
                        ->addComponent(new GridFieldTitleHeader())
                        ->addComponent($editableColumns)
                )->addExtraClass('staff-leave-gridfield');

                $fields->addFieldToTab('Root.Main', $grid);
            } else {
                $this->StaffLeave()->addMany($staffMembers);
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

    /**
     * @return string
     */
    public function getTitle()
    {
        return sprintf('%s - %s', $this->dbObject('StartDate')->Format('D jS M'), $this->dbObject('EndDate')->Format('D jS M'));
    }

    /**
     * @return Date
     */
    public function calculateEndDate()
    {
        $date = new Date();
        $date->setValue(date('Y-m-d', strtotime("+4 days", strtotime($this->StartDate))));
        return $date;
    }

    /**
     * @return array
     */
    public function getHolidayArray()
    {
        return explode(',', $this->Holidays);
    }

    /**
     * Creates default Staff Member group
     */
    public function requireDefaultRecords()
    {
        $staffGroup = Group::get()->filter(array('Code' => 'staff-members'));
        if (!$staffGroup->count()) {
            /** @var Group $staffGroup */
            $staffGroup = Group::create(
                array(
                    'Title' => _t('Roster.DefaultGroupTitleStaffMembers', 'Staff Members'),
                    'Code'  => 'staff-members'
                )
            );

            $staffGroup->write();
            Permission::grant($staffGroup->ID, 'VIEW_ROSTER');

            DB::alteration_message(_t('Roster.GroupCreated', 'Staff Members group created'), 'created');
        }
    }

    /**
     * @return DataObject
     */
    public static function getCurrentRoster()
    {
        return self::getRosterForDate(SS_Datetime::now()->Format('Y-m-d'));
    }

    /**
     * Uses the default sort on Roster to return the most recent one
     *
     * @return DataObject
     */
    public static function getLatestRoster()
    {
        return DataObject::get_one('Roster');
    }

    /**
     * Return a roster for a particular date
     *
     * @param $date
     * @return DataObject
     */
    public static function getRosterForDate($date)
    {
        $roster = Roster::get()->filter(array(
            'StartDate:LessThanOrEqual' => $date,
            'EndDate:GreaterThanOrEqual' => $date
        ));

        return $roster->first();
    }

    /**
     * Render with a custom template
     *
     * @return HTMLText
     */
    public function forTemplate()
    {
        return $this->renderWith('Roster');
    }

    /**
     * Template helper - Returns the start date, then the rest of the columns' dates
     *
     * @return ArrayList
     */
    public function getHeaderItems()
    {
        /** @var ArrayList $data */
        $data = ArrayList::create();

        $data->push(ArrayData::create(array(
            'Date' => $this->dbObject('StartDate')
        )));

        for ($i = 0; $i < 5; $i++) {
            $date = new Date();
            $thisDateString = date('d-m-Y', strtotime('+' . $i . ' days', strtotime($this->StartDate)));
            $date->setValue($thisDateString);

            $holidayClass = (in_array($date->Format('Y-m-d'), $this->getHolidayArray())) ? 'holiday' : '';

            $activeClass = (SS_Datetime::now()->Format('d-m-Y') == $thisDateString) ? 'active' : '';

            $data->push(new ArrayData(array(
                'Date'         => $date,
                'HolidayClass' => $holidayClass,
                'ActiveClass'  => $activeClass,
            )));
        }

        return $data;
    }

    /**
     * Roster row display for template
     *
     * @return ArrayList
     */
    public function getRows()
    {
        $rows = $this->WeeklyRosters();

        /** @var ArrayList $data */
        $data = ArrayList::create();

        /** @var JobRole $role */
        foreach ($rows->getIterator() as $role) {

            /** @var ArrayList $thisRow */
            $thisRow = ArrayList::create();

            $thisRow->push(ArrayData::create(array(
                'Item' => $role->Title
            )));

            for ($i = 0; $i < 5; $i ++) {
                $thisRow->push(ArrayData::create(array(
                    'Item' => Member::get()->filter(array('ID' => explode(',', $role->{"StaffAm{$i}"})))
                )));
                $thisRow->push(ArrayData::create(array(
                    'Item' => Member::get()->filter(array('ID' => explode(',', $role->{"StaffPm{$i}"})))
                )));
            }

            $data->push(ArrayData::create(array('Items' => $thisRow)));
        }

        return $data;
    }

    /**
     * Set the end date
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if ($this->isChanged('StartDate') || $this->getField('EndDate') == '') {
            $this->setField('EndDate', $this->calculateEndDate());
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
                'name'     => _t('Roster.ModifyRoster', 'Modify roster'),
                'help'     => _t('Roster.ModifyRosterHelp', 'User can create, edit, and delete rosters.')
            ),
            'VIEW_ROSTER' => array(
                'category' => _t('Roster.RosterPermissions', 'Roster Permissions'),
                'name'     => _t('Roster.ViewRoster', 'View Roster'),
                'help'     => _t('Roster.ViewRosterHelp', 'User can view the roster')
            )
        );
    }

    public function canCreate($member = null) {
        return Permission::check('MODIFY_ROSTER', 'any', $member);
    }

    public function canEdit($member = null) {
        return Permission::check('MODIFY_ROSTER', 'any', $member);
    }

    public function canDelete($member = null) {
        return Permission::check('MODIFY_ROSTER', 'any', $member);
    }

    public function canView($member = null) {
        return Permission::check('VIEW_ROSTER', 'any', $member);
    }
}