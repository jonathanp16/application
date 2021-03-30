<?php

namespace Tests\Browser;

use App\Models\Room;
use App\Models\User;
use Database\Seeders\EasyUserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Rooms;
use Tests\DuskTestCase;

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
        $room = Room::factory()->create([
            'name' => 'TestRoom',
        ]);
        User::first()->assignRole('super-admin');

    }

    public function testBookRoomForTimeIntervals()
    {

//        $room = Room::factory()->make();
        $this->browse(function (Browser $browser){
            $browser->loginAs(User::first())->visit('/bookings/search')
                ->assertSourceHas('<title>CSU Booking Platform</title>')
                ->assertSourceHas('<div>Action</div>')
                ->pressAndWaitFor('Action', 2)
                ->pressAndWaitFor('Create Booking', 1)
                ->value('#start_time', '2021-03-15T14:00')
                ->value('#end_time', '2021-03-15T14:00')
                ->press('CREATE');



        });
    }


}
