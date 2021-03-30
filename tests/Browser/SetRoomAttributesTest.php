<?php

namespace Tests\Browser;

use App\Models\Room;
use App\Models\User;
use Database\Seeders\EasyUserSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Rooms;
use Tests\DuskTestCase;

class SetRoomAttributesTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $seeder = new EasyUserSeeder();
        $seeder->run();
        $seeder = new RolesAndPermissionsSeeder();
        $seeder->run();
        User::first()->assignRole('super-admin');
    }

    public function testAddingAttributesOnRoomCreation()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first())->visit('/admin/rooms')
                ->assertSourceHas('<title>CSU Booking Platform</title>')
                ->click('#attributes')
                ->assertSee('Food')
                ->assertSee('Alcohol')
                ->assertSee('A/V Equipment Permitted')
                ->assertSee('Status')
                ->assertSee('Room type')
                ->assertSee('Equipment')
                ->assertSee('Restrictions')
                ->assertSee('Furniture')
                ->assertSee('Sofa(s)')
                ->assertSee('Coffee Table(s)')
                ->assertSee('Table(s)')
                ->assertSee('Chair(s)');
//                ->type('name', 'testname')
//                ->type('number', 'number')
//                ->value('#floor', 5)
//                ->value('#stand_capacity', 9);
        });
    }


}
