<?php

namespace Tests\Browser\Pages\Bookings;

use Illuminate\Support\Carbon;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;
use App\Models\Room;

class Search extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return route('bookings.search');
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs(str_replace(url('') ,'', $this->url()));
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }

    public function typeDateTime(Browser $browser, $selector = 'input', $date = null) {
        $date = Carbon::parse($date);
        $browser->keys($selector,
            $date->format('Y'),
            ['{right}', $date->format('m')],
            $date->format('d'),
            $date->format('h'),
            $date->format('i'),
            $date->format('A'),
        );
    }

    public function reserveRoom(Browser $browser, Room $room, $start, $end){
        $browser->click("@room-actions-{$room->id}");
        $browser->waitFor("@room-select-{$room->id}", 5);
        $browser->click("@room-select-{$room->id}");

        $browser->typeDateTime('#start_time', $start);
        $browser->typeDateTime('#end_time', $end);

        $browser->pause(1000)
            ->press('CREATE')
            ->pause(1000);
    }


}
