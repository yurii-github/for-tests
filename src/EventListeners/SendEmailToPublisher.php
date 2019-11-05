<?php

namespace dio\EventListeners;

use Symfony\Contracts\EventDispatcher\Event;

class SendEmailToPublisher implements ListenerInterface
{
    public function onEvent(Event $event)
    {
        //mb_send_mail(...);
        var_dump($event);
    }
}
