<?php

declare(strict_types=1);

namespace Storm\Reporter;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Storm\Contract\Message\MessageFactory;
use Storm\Contract\Message\MessageProducer;
use Storm\Contract\Serializer\MessageSerializer;
use Storm\Message\GenericMessageFactory;
use Storm\Message\SyncMessageProducer;
use Storm\Serializer\JsonSerializer;
use Storm\Serializer\MessageContentSerializer;
use Storm\Serializer\MessagingSerializer;
use Storm\Support\Attribute\AttributeResolver;
use Storm\Support\ContainerAsClosure;

class ReporterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MessageSerializer::class, function () {
            return new MessagingSerializer(
                (new JsonSerializer())->create(),
                new MessageContentSerializer()
            );
        });

        $this->app->singleton(MessageFactory::class, GenericMessageFactory::class);
        $this->app->singleton(MessageProducer::class, SyncMessageProducer::class);

        //
        $this->app->bind(ContainerAsClosure::class, function (Application $app) {
            return new ContainerAsClosure(fn () => $app);
        });

        $this->app->resolving(AttributeResolver::class, function (AttributeResolver $resolver, Application $app) {
            $resolver->setContainer($app);

            return $resolver;
        });
    }
}