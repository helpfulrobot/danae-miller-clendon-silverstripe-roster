<?php
/**
 * Author: danae-miller-clendon
 * Date: 2/06/15
 * Time: 10:11 AM
 */

/**
 * Class RosterTest
 *
 * @mixin \PHPUnit_Framework_TestCase
 */
class RosterTest extends SapphireTest
{
    protected static $fixture_file = 'fixtures/RosterTestFixture.yml';

    public function testRosterCreation()
    {
        /** @var Roster $roster */
        $roster = $this->objFromFixture('Roster', 'current');
        $roster->write();

        // Check that the end date is correct
        $this->assertEquals('2015-06-05', $roster->EndDate);
    }
}