<?php

declare(strict_types=1);

namespace Framework;

use ErrorException;
use Throwable;
use Framework\Exceptions\PageNotFoundException;

class ErrorHandler
{
  public static function handleError(int $errorNum, string $errorString, string $errorFile, int $errorLine): bool
  {
    throw new ErrorException($errorString, 0, $errorNum, $errorFile, $errorLine);
  }

  public static function handleException(Throwable $exception): void
  {
    if ($exception instanceof PageNotFoundException) {
      $errorCode = 404;
    } else {
      $errorCode = 500;
    }

    http_response_code($errorCode);

    $showErrors = true;

    if ($showErrors) {
      ini_set("display_errors", "1");
    } else {
      ini_set("display_errors", "0");
      ini_set("log_errors", "1");

      require "views/{$errorCode}.php";
    }

    throw $exception;
  }
}
