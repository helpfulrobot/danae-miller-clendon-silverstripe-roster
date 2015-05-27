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

    public function __construct($startDate = null)
    {
        if ($startDate) {
            $this->startDate = $startDate;
        } else {
            $this->startDate = Date::create();
        }
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
            $days->push(new ArrayData(array(
                'Day'  => date('l', strtotime('+'.$i.' days', strtotime($this->startDate)))
            )));
        }

        return array(
            'header' => $cols->renderWith('RosterGridFieldTitleHeader', array('StartDate' => $this->startDate, 'Days' => $days))
        );
    }

}
