<?php

class BiscuitException extends Exception
{
    private $exception_msg;

    function __construct($msg = 'No Error Message Given')
    {
        parent::__construct($msg);
        $this->exception_msg = $msg;
    }

    public function getExceptionMessage()
    {
        $formatted_message = "\n*** BISCUIT Exception: ***\n - " . $this->exception_msg;
        return $formatted_message;
    }
}
