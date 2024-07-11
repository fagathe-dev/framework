<?php 
namespace Fagathe\Framework\Database;

use Fagathe\Framework\Exception\Exception;

final class DatabaseException extends Exception
{
    public function __construct(?string $message = '') {
        $this->message = $message;
        $this->code = 500;
        $this->statusText = "Database exception";
        $this->name = 'DatabaseException';
    }

}