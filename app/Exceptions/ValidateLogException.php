<?php

namespace App\Exceptions;

use Exception;

class ValidateLogException extends Exception
{
    public $errors;

    public $message;

    public function __construct($errors, $message = '必填信息没有填完')
    {
        $this->errors = $errors;
        $this->message = $message;
    }


    public function getErrors()
    {
        return $this->errors;
    }

    public function getMessages()
    {
        return $this->message;
    }

}
