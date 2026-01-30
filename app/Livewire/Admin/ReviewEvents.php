<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use App\Services\PaymentService;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Person;
use App\Models\Beneficiary;
use App\Models\PaymentCycle;
use App\Models\Payout;
use App\Models\Dependent;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Services\SeedPaymentService;
use App\Services\ReplenishmentService;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventApprovedMail;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\Setting;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use App\Services\PayPalPayoutService;

class ReviewEvents extends Component
{

    public $search = '';
    use WithPagination;


    public bool $seedInsufficient = false;

    public function mount()
    {
        $approvedAmount = Setting::get('approved_amount');
        $year = now()->year;

        $this->seedInsufficient = !SeedPaymentService::hasSufficientBalance(
            $approvedAmount,
            $year
        );
    }

public function openReplenishment($eventId)
{
    $event = Event::findOrFail($eventId);
    $year = now()->year;
    $approvedAmount = Setting::get('approved_amount');

    $existingCycle = PaymentCycle::where('type', 'replenishment')
        ->where('year', $year)
        ->where('status', 'open')
        ->first();

    if ($existingCycle) {
        session()->flash('error', "A replenishment cycle is already open. Please use the existing cycle.");
        return;
    }

    try {
        $cycle = ReplenishmentService::createSeedReplenishmentCycle(
            $year,
            $approvedAmount
        );

        session()->flash('success', "Replenishment cycle opened. Each member contributes KES {$cycle->amount_per_member}.");
    } catch (\Exception $e) {
        session()->flash('error', $e->getMessage());
    }

    $this->mount(); 
}


    public function refreshSeedStatus()
    {
        $this->mount();
    }
 protected function generateEventPoster(Event $event)
{
    try {
        $fontPath = public_path('fonts/Arial-Bold.ttf');
        if (!file_exists($fontPath)) {
            \Log::warning("Font file 'Arial-Bold.ttf' not found. Using default font.");
            $fontPath = null; 
        }
       $manager = new ImageManager(Driver::class);
       $img = $manager->create(512, 512)->fill('ccc');

        $title = $event->details->title ?? 'Event Title';
        $img->text($title, 400, 300, function($font) use ($fontPath) {
            if ($fontPath) {
                $font->file($fontPath);
            }
            $font->size(40);
            $font->color('#000000');
            $font->align('center');
            $font->valign('middle');
        });
        if (!empty($event->details->date)) {
            $img->text($event->details->date, 400, 380, function($font) use ($fontPath) {
                if ($fontPath) {
                    $font->file($fontPath);
                }
                $font->size(24);
                $font->color('#333333');
                $font->align('center');
                $font->valign('middle');
            });
        }
        $fileName = 'event_' . $event->id . '_poster.jpg';
        $img->save(storage_path('app/public/events/' . $fileName));
        return 'events/' . $fileName;
    } catch (\Exception $e) {
        \Log::error("Error generating event poster: " . $e->getMessage());
        return 'events/default-poster.jpg';
    }
}



    public function approve($eventId)
{
    try {
        DB::transaction(function () use ($eventId) {

            $event = Event::with(['person', 'member'])->findOrFail($eventId);

            $approvedAmount = Setting::get('approved_amount');
            $currentYear = now()->year;

            // 🚫 HARD STOP if insufficient seed fund
            if (!SeedPaymentService::hasSufficientBalance($approvedAmount, $currentYear)) {
                throw new \Exception(
                    'Insufficient seed fund. Available balance: ' .
                    number_format(SeedPaymentService::balance($currentYear), 2) .
                    '. Please open a replenishment cycle.'
                );
            }

            // Update person + beneficiaries
            $event->person->update([
                'deceased_at' => now(),
                'status' => 'deceased',
            ]);

            $event->person->beneficiaries()->update([
                'percentage' => 0,
                'status' => 'deceased',
            ]);

            // Deduct seed fund
            SeedPaymentService::deductForEvent($event);

            // Approve event
            $event->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_amount' => $approvedAmount,
                'paid_from_seed' => true,
                'ready_for_payout' => true,
            ]);

            // $this->createPayout($event);
            // Create event details
            $event->load('person');
            $event->details()->create([
                'title' =>  $event->person->name . ' Bereavement Support Event',
                'description' => 'Support event for families dealing with bereavement.',
                'event_date' => now()->addDays(14),
                'location' => 'Carolina East Africa Foundation Hall',
                'public_image' => $this->generateEventPoster($event),
                'is_published' => false,
            ]);
        });

        session()->flash('success', 'Event approved successfully.');

    } catch (\Exception $e) {
        // Admin notification
        session()->flash('error', $e->getMessage());
    }

    $this->resetPage();
}


public function processPayout(Event $event)
{
    if (!$event->ready_for_payout) {
        session()->flash('error', 'Event is not ready for payout.');
        return;
    }

    $member = $event->member;
    $paypalEmail = $member->paypal_email ?? $member->user->email;

    if (!$paypalEmail) {
        session()->flash('error', 'Cannot process payout: no email available for the member.');
        return;
    }

    try {
        $paypalService = new PayPalPayoutService();


        $transactionId = $paypalService->sendPayout(
            $paypalEmail,
            $event->approved_amount,
            'USD' 
        );

        // Update event with payout details
        $event->update([
            'payout_status' => 'success',
            'payout_at' => now(),
            'paypal_transaction_id' => $transactionId,
            'paypal_email' => $paypalEmail,
        ]);

        // Update member record if PayPal email was missing
        if (!$member->paypal_email) {
            $member->update(['paypal_email' => $paypalEmail]);
        }

        session()->flash('success', 'Payout processed successfully.');

    } catch (\Exception $e) {
        $event->update([
            'payout_status' => 'failed',
        ]);
        session()->flash('error', 'Payout failed: ' . $e->getMessage());
    }
}


    public function reject($eventId)
    {
        $event = Event::findOrFail($eventId);
        $event->update(['status' => 'rejected']);
        session()->flash('success', 'Event rejected.');
       $this->resetPage();
    }

  public function render()
{
     $events = Event::with(['person', 'member', 'documents','paymentCycle'])
            ->when($this->search, function ($query) {
                $query->whereHas('person', fn($q) =>
                    $q->where('first_name', 'like', "%{$this->search}%")
                      ->orWhere('last_name', 'like', "%{$this->search}%")
                );
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.review-events', [
            'events' => $events,
        ])->layout('layouts.admin');
    }



    public function publish($eventId)
{
    $event = Event::with('details')->findOrFail($eventId);

    // Ensure the event details exist before publishing
    if ($event->details) {
        $event->details->update(['is_published' => true]);
        session()->flash('success', 'Event published successfully.');
    } else {
        session()->flash('error', 'Event details not found.');
    }

    $this->resetPage();
}
}