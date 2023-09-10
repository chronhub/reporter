<?php

declare(strict_types=1);

namespace Storm\Reporter\Subscriber;

use Storm\Attribute\Reference;
use Storm\Contract\Message\MessageFactory;
use Storm\Contract\Reporter\Reporter;
use Storm\Contract\Tracker\MessageStory;
use Storm\Reporter\Attribute\AsSubscriber;

#[AsSubscriber(eventName: Reporter::DISPATCH_EVENT, priority: 100000)]
final readonly class MakeMessage
{
    public function __construct(#[Reference] private MessageFactory $messageFactory)
    {
    }

    public function __invoke(): callable
    {
        return function (MessageStory $story): void {
            $message = $this->messageFactory->createMessageFrom($story->pullTransientMessage());

            $story->withMessage($message);
        };
    }
}
