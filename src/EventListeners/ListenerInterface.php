<?php
namespace dio\EventListeners;

use Symfony\Contracts\EventDispatcher\Event;

interface ListenerInterface
{
    public function onEvent(Event $event);
}
