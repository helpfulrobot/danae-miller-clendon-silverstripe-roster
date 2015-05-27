<?php
/**
 * Author: danae-miller-clendon
 * Date: 27/05/15
 * Time: 10:03 AM
 */

/**
 * Class RosterMember
 */
class RosterMember extends DataExtension
{
    private static $db = array(
        'Initials' => 'Varchar(5)'
    );

    public function getInitials()
    {
        if ($this->owner->getField('Initials') == '') {
            $surname   = $this->owner->getField('Surname');
            $firstName = $this->owner->getField('FirstName');

            if ($surname && $firstName) {
                return strtoupper($firstName[0] . $surname[0]);
            } elseif ($surname) {
                return strtoupper($surname[0]);
            } elseif ($firstName) {
                return strtoupper($firstName[0]);
            } else {
                return '';
            }
        } else {
            return $this->owner->getField('Initials');
        }
    }
}