<?php
/**
 * Author: danae-miller-clendon
 * Date: 26/05/15
 * Time: 11:40 AM
 */

/**
 * Class JobRole
 */
class JobRole extends DataObject
{
    private static $singular_name = 'Job Role';
    private static $plural_name = 'Job Roles';
    private static $description = 'Represents role that staff members can be assigned to';

    private static $db = array(
        'Title' => 'Varchar(500)',
        'Phone' => 'Varchar(30)'
    );

    private static $has_one = array(
        'Location' => 'Location'
    );
}