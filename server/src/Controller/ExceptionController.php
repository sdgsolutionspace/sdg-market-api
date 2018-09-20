<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController extends FOSRestController
{

    /**
     * @param Request $request
     * @param \Exception|\Throwable $exception
     * @param DebugLoggerInterface|null $logger
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null)
    {
        if($exception instanceof NotFoundHttpException){
            return [
                'code'=>404,
                'message'=> 'Object not found'
            ];
        }

        $exceptionController = $this->get('fos_rest.exception.controller');

        return $exceptionController->showAction($request, $exception, $logger);
    }
}
