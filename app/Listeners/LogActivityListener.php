<?php

namespace App\Listeners;

use App\Events\LogActivityEvent;
use App\Models\AuditTrail;

class LogActivityListener
{
    /**
     * @var AuditTrail
     */
    private AuditTrail $auditTrail;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AuditTrail $auditTrail)
    {
        $this->auditTrail = $auditTrail;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(LogActivityEvent $event): void
    {
        $this->auditTrail->create([
            'type' => $event->getType(),
            'description' => $event->getDescription(),
            'old_data' => $event->getOldData(),
            'new_data' => $event->getNewData(),
            'user_id' => $event->getUserId(),
        ]);
    }
}
