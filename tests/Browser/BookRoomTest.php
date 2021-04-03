<?php

namespace Tests\Browser;

use App\Models\Room;
use App\Models\User;
use Database\Seeders\EasyUserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Bookings\Search;
use Tests\Browser\Pages\Rooms;
use Tests\DuskTestCase;
use Illuminate\Support\Carbon;

class BookRoomTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $seeder = new EasyUserSeeder();
        $seeder->run();
        $seeder = new RolesAndPermissionsSeeder();
        $seeder->run();
        $seeder = new RoomSeeder();
        $seeder->run();
        User::first()->assignRole('super-admin');

    }

    /**
     * Create booking at available time,
     * assert that user redirected to creation page
     */
    public function testBookRoomForTimeIntervals()
    {
        $this->browse(function (Browser $browser){

            $admin = User::first();

            if($admin === null) {

                $admin = User::factory()->create();
                $admin->assignRole('super-admin');
            }

            $browser->loginAs($admin);
            $browser->visit(new Search);

            $browser
                ->assertSourceHas('<title>CSU Booking Platform</title>');

            $room = Room::inRandomOrder()->first();

            $room->min_days_advance = 0;
            $room->max_days_advance = 10000;
            $room->save();

            $browser->reserveRoom($room, '2021-03-30 01:15PM', '2021-03-30 02:15PM');
            $browser->waitForLocation('/bookings/create');
        });
    }

    /**
     * Create booking at unavailable time,
     * assert that error message shows up
     */
    public function testCannotBookUnavailableTime()
    {
        $this->browse(function (Browser $browser){

            $admin = User::first();

            if($admin === null) {

                $admin = User::factory()->create();
                $admin->assignRole('super-admin');
            }

            $browser->loginAs($admin);
            $browser->visit(new Search);

            $browser
                ->assertSourceHas('<title>CSU Booking Platform</title>');

            $room = Room::inRandomOrder()->first();

            $browser->reserveRoom($room, '2021-03-30 01:15AM', '2021-03-30 02:15AM');

            $browser->assertSee('These dates and times are not within the room\'s availabilities!');
        });
    }

    /**
     * Create booking and check off bake sale,
     * assert that bake sale form prompt pops up,
     * attach file and assert it is visible
     */
    public function testPromptedToDownloadFormAndAttach()
    {
        $this->browse(function (Browser $browser){

            $admin = User::first();

            if($admin === null) {

                $admin = User::factory()->create();
                $admin->assignRole('super-admin');
            }

            $browser->loginAs($admin);
            $browser->visit(new Search);

            $browser
                ->assertSourceHas('<title>CSU Booking Platform</title>');

            $room = Room::where('name', 'Art Nook')->firstOrFail();

            $room->min_days_advance = 0;
            $room->max_days_advance = 10000;
            $room->save();

            $browser->reserveRoom($room, '2021-03-30 01:15PM', '2021-03-30 02:15PM');

            $browser->waitForLocation('/bookings/create');
            $browser->script('document.getElementById(\'bakeSaleCheckbox\').scrollIntoView();');
            $browser->check("#bakeSaleCheckbox");
            $browser->assertSee('Please remember to attach a filled out "Bake Sale Form" to the document section');
            $browser->attach('#files', __DIR__ . '/test.jpg');
            $browser->assertSee('test.jpg');
        });
    }
}
