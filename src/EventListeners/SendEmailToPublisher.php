<?php

namespace dio\EventListeners;

use dio\Events\BlacklistUpdated;

class SendEmailToPublisher implements ListenerInterface
{
    /**
     * @param BlacklistUpdated $event
     */
    public function onEvent($event)
    {
        $unchanged = array_intersect($event->getNewBlackList(), $event->getOldBlackList());

        //added
        foreach ($event->getNewBlackList() as $publisherId) {
            if (in_array($publisherId, $unchanged)) {
                continue;
            }
            //mb_send_mail(...);
            file_put_contents(dirname(__DIR__, 2) . '/email.log', ":ADDED: $publisherId\n", FILE_APPEND);
        }

        // removed
        foreach ($event->getOldBlackList() as $publisherId) {
            if (in_array($publisherId, $unchanged)) {
                continue;
            }
            //mb_send_mail(...);
            file_put_contents(dirname(__DIR__, 2) . '/email.log', ":REMOVED: $publisherId\n", FILE_APPEND);
        }
    }
}
