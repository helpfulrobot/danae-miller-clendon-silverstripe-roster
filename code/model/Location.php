<?php
/**
 * Author: danae-miller-clendon
 * Date: 26/05/15
 * Time: 11:58 AM
 */

/**
 * Class Location
 */
class Location extends \DataObject
{
    private static $singular_name = 'Location';
    private static $plural_name = 'Locations';
    private static $description = 'Represents a location for work, eg; office, clinic';

    private static $db = array(
        'Title'   => 'Varchar(500)',
        'Code'    => 'Varchar(10)',
        'Phone'   => 'Varchar(30)',
        'Email'   => 'Varchar(1000)',
        'Address' => 'Text'
    );

    private static $summary_fields = array(
        'Title',
        'Code',
        'Phone',
        'Email',
        'Address'
    );
}