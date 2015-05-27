<?php

/**
 * A simple header which displays column titles.
 */
class RosterGridFieldTitleHeader implements GridField_HTMLProvider
{
    /**
     * @var Date $startDate
     */
    private $startDate;

    /**
     * @var array
     */
    private $holidays;

    public function __construct($startDate = null, $holidayArray = array())
    {
        if ($startDate) {
            $this->startDate = $startDate;
        } else {
            $this->startDate = Date::create();
        }

        $this->holidays = $holidayArray;
    }

    /**
     * @param GridField $grid
     * @return array
     */
    public function getHTMLFragments($grid)
    {
        $cols = new ArrayList();

        foreach ($grid->getColumns() as $name) {
            $meta = $grid->getColumnMetadata($name);

            $cols->push(new ArrayData(array(
                'Name'  => $name,
                'Title' => $meta['title']
            )));
        }

        $days = new ArrayList();

        for ($i = 0; $i < 5; $i++) {
            $date = new Date();
            $date->setValue(date('d-m-Y', strtotime('+'.$i.' days', strtotime($this->startDate))));

            $isHoliday = (in_array($date->Format('Y-m-d'), $this->holidays));

            $days->push(new ArrayData(array(
                'Day'       => $date->Format('l'),
                'IsHoliday' => $isHoliday
            )));
        }

        return array(
            'header' => $cols->renderWith('RosterGridFieldTitleHeader', array('StartDate' => $this->startDate, 'Days' => $days))
        );
    }

}
