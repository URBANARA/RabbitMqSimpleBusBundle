<?php

declare(strict_types=1);

namespace SyliusLabs\RabbitMqSimpleBusBundle\Consumer;

use Interop\Amqp\AmqpMessage;
use Psr\Log\LoggerInterface;
use SyliusLabs\RabbitMqSimpleBusBundle\Bus\MessageBusInterface;
use SyliusLabs\RabbitMqSimpleBusBundle\Denormalizer\DenormalizerInterface;

final class RabbitMqConsumer
{
    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param DenormalizerInterface $denormalizer
     * @param MessageBusInterface $messageBus
     * @param LoggerInterface $logger
     */
    public function __construct(
        DenormalizerInterface $denormalizer,
        MessageBusInterface $messageBus,
        LoggerInterface $logger
    ) {
        $this->denormalizer = $denormalizer;
        $this->messageBus = $messageBus;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(AmqpMessage $message): void
    {
        try {
            $denormalizedMessage = $this->denormalizer->denormalize($message);

            $this->messageBus->handle($denormalizedMessage);
        } catch (\Throwable $throwable) {
            $this->logger->error(sprintf(
                'Exception "%s" while handling an AMQP message: "%s". Stacktrace: %s',
                $throwable->getMessage(),
                $message->getBody(),
                $throwable->getTraceAsString()
            ));
        }
    }
}
