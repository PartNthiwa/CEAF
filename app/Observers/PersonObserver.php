<?php

namespace App\Observers;

use App\Models\Person;

class PersonObserver
{
    /**
     * Handle the Person "created" event.
     */
    public function created(Person $person): void
    {
        //
    }

    /**
     * Handle the Person "updated" event.
     */
    public function updated(Person $person): void
    {
       if (
            $person->isDirty('deceased_at') &&
            $person->deceased_at !== null
        ) {
            // Lock the person
            $person->updateQuietly([
                'status' => 'deceased',
            ]);

            // Sync dependent
            if ($person->dependent) {
                $person->dependent->update([
                    'status' => 'deceased',
                ]);
            }

            // Sync beneficiary
            if ($person->beneficiary) {
                $person->beneficiary->update([
                    'status' => 'deceased',
                ]);
            }
        }
    }

    /**
     * Handle the Person "deleted" event.
     */
    public function deleted(Person $person): void
    {
        //
    }

    /**
     * Handle the Person "restored" event.
     */
    public function restored(Person $person): void
    {
        //
    }

    /**
     * Handle the Person "force deleted" event.
     */
    public function forceDeleted(Person $person): void
    {
        //
    }
}
