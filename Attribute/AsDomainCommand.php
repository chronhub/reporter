<?php

declare(strict_types=1);

namespace Storm\Reporter\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class AsDomainCommand
{
    public function __construct(
        public string|object $messageHandler,
        public bool $isAsync = false
    ) {
    }
}
