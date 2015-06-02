<?php
/**
 * Author: danae-miller-clendon
 * Date: 26/05/15
 * Time: 12:05 PM
 */

/**
 * Class RosterAdmin
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

    public function getEditForm($id = null, $fields = null)
    {
        /** @var Form $form */
        $form = parent::getEditForm($id, $fields);

        /** @var GridField $gridField */
        $gridField = $form->Fields()->fieldByName('JobRole');

        if ($gridField) {
            $gridField->getConfig()->addComponent(new GridFieldOrderableRows('Sort'));
        }

        return $form;
    }
}