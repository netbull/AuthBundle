<?php

namespace NetBull\AuthBundle\Security;

use Monolog\LogRecord;
use Symfony\Component\HttpFoundation\RequestStack;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedActivationStrategy extends ErrorLevelActivationStrategy
{
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        parent::__construct('critical');
        $this->requestStack = $requestStack;
    }

    /**
     * @param LogRecord $record
     * @return bool
     */
    public function isHandlerActivated(LogRecord $record): bool
    {
        $isActivated = parent::isHandlerActivated($record);

        if (
            $isActivated
            && isset($record['context']['exception'])
            && $record['context']['exception'] instanceof AccessDeniedException
            && $this->requestStack->getMainRequest()
        ) {
            return false;
        }

        return $isActivated;
    }
}
