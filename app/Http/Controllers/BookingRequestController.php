<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\BookingRequest;
use App\Models\Reservation;
use App\Models\Room;
use App\Events\BookingRequestUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use ZipArchive;
use File;

class BookingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Inertia\Response|\Inertia\ResponseFactory
     */
    public function index(Request $request)
    {
        return inertia('Admin/BookingRequests/Index', [
            'booking_requests' => BookingRequest::with('user', 'reservations', 'reservations.room')->get(),
            'rooms' => Room::hideUserRestrictions($request->user())->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        return inertia('Requestee/BookingForm', [
            // example of the expected reservations format
            'room' => Room::findOrFail($request->room_id),
            'reservations' => $request->reservations,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateBookingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBookingRequest $request)
    {
        $data = $request->validated();
        $room = Room::findOrFail($data['room_id']);
        $reservation = $data['reservations'][0];

        // validate room still available at given times
        foreach ($data['reservations'] as $value) {
          $room->verifyDatesAreWithinRoomRestrictions($value['start_time'], $value['end_time']);
          $room->verifyDatetimesAreWithinAvailabilities($value['start_time'], $value['end_time']);
          $room->verifyRoomIsFree($value['start_time'], $value['end_time']);
          if (!$request->user()->canMakeAnotherBookingRequest($value['start_time'])) {
            throw ValidationException::withMessages([
              'booking_request_exceeded' => 'Cannot make more than ' .
                $request->user()->getUserNumberOfBookingRequestPerPeriod() .
                ' bookings in the next ' .
                $request->user()->getUserNumberOfDaysPerPeriod() .
                ' days.'
            ]);
          }
        }

        if (array_key_exists('files', $data)) {
            // save the uploaded files
            $referenceFolder = "{$room->id}_".strtotime($reservation['start_time']).'_reference';

            foreach($data['files'] as $file) {
                $name = $file->getClientOriginalName();
                Storage::disk('public')->putFileAs($referenceFolder. '/', $file, $name);
            }
        }
        \DB::beginTransaction();

        // store booking in db
        $booking = BookingRequest::create([
            'user_id' => $request->user()->id,
            'start_time' => $reservation['start_time'],
            'end_time' => $reservation['end_time'],
            'status' => 'review',
            'event' => $data['event'],
            'onsite_contact' => $data['onsite_contact'] ?? [],
            'notes' => $data['notes'] ?? '',
            'reference' => (array_key_exists('files', $data) && count($data['files']) > 0) ?
                ["path" => $referenceFolder] : [],
        ]);


        foreach ($data['reservations'] as $reservation) {
            Reservation::create([
                'room_id' => $room->id,
                'booking_request_id' => $booking->id,
                'start_time' => $reservation['start_time'],
                'end_time' => $reservation['end_time'],
            ]);
        }

        \DB::commit();

        $log = '[' . date("F j, Y, g:i a") . '] - Created booking request';
        BookingRequestUpdated::dispatch($booking, $log);

        return redirect()->route('bookings.list')
            ->with('flash', ['banner' => 'Your Booking Request was submitted']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    // public function show(BookingRequest $bookingRequest)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(BookingRequest $booking)
    {
        return inertia('Requestee/EditBookingForm', [
            'booking' => $booking->load('user', 'reservations', 'reservations.room'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateBookingRequest  $request
     * @param  \App\Models\BookingRequest  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookingRequest $request, BookingRequest $booking)
    {
        $update = collect($request->validated())->except(['files']);
        $booking->fill($update->toArray())->save();

        if($booking->wasChanged()) {
            $log = '[' . date("F j, Y, g:i a"). '] - Updated booking request location and/or date';
            BookingRequestUpdated::dispatch($booking, $log);
        }

        if ($request->file()) {
/*            if(!array_key_exists('path', $booking->reference)) {
              $referenceFolder = "{$room->id}_".strtotime($reservation['start_time']).'_reference';
            }*/
            // save the uploaded files
            foreach($request->allFiles()['files'] as $file) {
                $name = $file->getClientOriginalName();
                Storage::disk('public')->putFileAs($booking->reference['path'] . '/', $file, $name);
            }
        }

        return redirect(route('bookings.list'))
            ->with('flash', ['banner' => 'Your Booking Request was updated!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookingRequest  $bookingRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingRequest $booking)
    {
        Reservation::where('booking_request_id', $booking->id)->delete();
        $booking->delete();

        return redirect(route('bookings.index'));
    }

    public function downloadReferenceFiles($folder)
    {
        $path = Storage::disk('public')->path($folder);

        $zip = new ZipArchive;

        $fileName = $folder . '.zip';

        $zip->open(Storage::disk('public')->path($fileName), ZipArchive::CREATE);
        $files = File::files($path);

        foreach ($files as $file) {
            $relativeNameInZipFile = basename($file);
            $zip->addFile($file, $relativeNameInZipFile);
        }
        $zip->close();

        return response()->download(Storage::disk('public')->path($fileName))->deleteFileAfterSend(true);

    }

    public function list(Request $request)
    {
        return inertia('Requestee/BookingsList', [
            'bookings' => BookingRequest::with('user','reservations.room')->get(),

        ]);
    }



}
