<?php

namespace Laravel\Reverb;

use Illuminate\Support\Str;

class ClientEvent
{
    /**
     * Handle a pusher event.
     */
    public static function handle(Connection $connection, array $event): ClientEvent|null
    {
        if (! Str::startsWith($event['event'], 'client-')) {
            return null;
        }

        if (! isset($event['channel'])) {
            return null;
        }

        return self::whisper(
            $connection,
            $event
        );
    }

    /**
     * Whisper a message to all connection of the channel.
     */
    public static function whisper(Connection $connection, array $payload): void
    {
        Event::dispatch(
            $connection->app(),
            $payload + ['except' => $connection->identifier()],
            $connection
        );
    }
}
