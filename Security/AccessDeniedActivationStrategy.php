<?php

namespace NetBull\AuthBundle\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class AccessDeniedActivationStrategy
 * @package NetBull\AuthBundle\Security
 */
class AccessDeniedActivationStrategy extends ErrorLevelActivationStrategy
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * AccessDeniedActivationStrategy constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        parent::__construct('critical');

        $this->requestStack = $requestStack;
    }

    /**
     * @param array $record
     * @return bool
     */
    public function isHandlerActivated(array $record)
    {
        $isActivated = parent::isHandlerActivated($record);

        if (
            $isActivated
            && isset($record['context']['exception'])
            && $record['context']['exception'] instanceof AccessDeniedException
            && ($request = $this->requestStack->getMasterRequest())
        ) {
            return false;
        }

        return $isActivated;
    }
}