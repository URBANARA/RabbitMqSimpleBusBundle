<?php

declare(strict_types=1);

namespace SyliusLabs\RabbitMqSimpleBusBundle\Denormalizer;

use Interop\Amqp\AmqpMessage;

interface DenormalizerInterface
{
    /**
     * @param AmqpMessage $message
     *
     * @return bool
     */
    public function supports(AmqpMessage $message): bool;

    /**
     * @param AmqpMessage $message
     *
     * @return object
     *
     * @throws DenormalizationFailedException
     */
    public function denormalize(AmqpMessage $message);
}
