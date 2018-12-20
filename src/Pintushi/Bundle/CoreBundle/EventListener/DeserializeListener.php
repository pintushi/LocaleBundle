<?php

namespace Pintushi\Bundle\CoreBundle\EventListener;

use ApiPlatform\Core\Api\FormatsProviderInterface;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Pintushi\Bundle\OrganizationBundle\Ownership\RecordOwnerData;

/**
 *  Set ownership  to entity
 */
final class DeserializeListener
{
    private $serializer;
    private $serializerContextBuilder;
    private $formats = [];
    private $formatsProvider;
    private $recordOwnerData;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        SerializerInterface $serializer,
        SerializerContextBuilderInterface $serializerContextBuilder,
        /* FormatsProviderInterface */ $formatsProvider,
        RecordOwnerData $recordOwnerData
    ) {
        $this->serializer = $serializer;
        $this->serializerContextBuilder = $serializerContextBuilder;
        $this->recordOwnerData = $recordOwnerData;

        if (\is_array($formatsProvider)) {
            @trigger_error('Using an array as formats provider is deprecated since API Platform 2.3 and will not be possible anymore in API Platform 3', E_USER_DEPRECATED);
            $this->formats = $formatsProvider;

            return;
        }
        if (!$formatsProvider instanceof FormatsProviderInterface) {
            throw new InvalidArgumentException(sprintf('The "$formatsProvider" argument is expected to be an implementation of the "%s" interface.', FormatsProviderInterface::class));
        }

        $this->formatsProvider = $formatsProvider;
    }

    /**
     * Deserializes the data sent in the requested format.
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();
        if ($request->isMethodSafe(false)
            || 'DELETE' === $method
            || !($attributes = RequestAttributesExtractor::extractAttributes($request))
            || !$attributes['receive']
            || (
                    '' === ($requestContent = $request->getContent())
                    && ('POST' === $method || 'PUT' === $method)
               )
        ) {
            return;
        }
        // BC check to be removed in 3.0
        if (null !== $this->formatsProvider) {
            $this->formats = $this->formatsProvider->getFormatsFromAttributes($attributes);
        }

        $format = $this->getFormat($request);
        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attributes);

        $data = $request->attributes->get('data');
        if (null !== $data) {
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $data;
        }

        $data =  $this->serializer->deserialize(
            $requestContent,
            $attributes['resource_class'],
            $format,
            $context
        );

        $this->recordOwnerData->process($data);

        $request->attributes->set(
            'data',
            $data
        );
    }

    /**
     * Extracts the format from the Content-Type header and check that it is supported.
     *
     * @param Request $request
     *
     * @throws NotAcceptableHttpException
     *
     * @return string
     */
    private function getFormat(Request $request): string
    {
        /**
         * @var string|null
         */
        $contentType = $request->headers->get('CONTENT_TYPE');
        if (null === $contentType) {
            throw new NotAcceptableHttpException('The "Content-Type" header must exist.');
        }

        $format = $request->getFormat($contentType);
        if (null === $format || !isset($this->formats[$format])) {
            $supportedMimeTypes = [];
            foreach ($this->formats as $mimeTypes) {
                foreach ($mimeTypes as $mimeType) {
                    $supportedMimeTypes[] = $mimeType;
                }
            }

            throw new NotAcceptableHttpException(sprintf(
                'The content-type "%s" is not supported. Supported MIME types are "%s".',
                $contentType,
                implode('", "', $supportedMimeTypes)
            ));
        }

        return $format;
    }
}
