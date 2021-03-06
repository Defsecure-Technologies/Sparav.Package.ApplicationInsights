<?php

namespace Sparav\ApplicationInsights\Handlers;

use Exception;
use Illuminate\Container\Container;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sparav\ApplicationInsights\MSApplicationInsightsHelpers;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class MSApplicationInsightsExceptionHandler extends ExceptionHandler
{

    /**
     * @var MSApplicationInsightsHelpers
     */
    private $msApplicationInsightsHelpers;

    public function __construct(MSApplicationInsightsHelpers $msApplicationInsightsHelpers, ContainerInterface $log)
    {
       $this->msApplicationInsightsHelpers = $msApplicationInsightsHelpers;
       parent::__construct($log);
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(\Throwable $e)
    {
        foreach ($this->dontReport as $type)
        {
            if ($e instanceof $type)
            {
                return parent::report($e);
            }
        }

        $this->msApplicationInsightsHelpers->trackException($e);

        return parent::report($e);
    }
}
