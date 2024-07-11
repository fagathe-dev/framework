<?php 
namespace Fagathe\Framework\Helpers;

trait DateTimeHelperTrait
{
    public function __toString(): string
    {
        return (new \DateTimeImmutable('now', timezone: new \DateTimeZone(DEFAULT_DATE_TIMEZONE)))->format('Y-m-d H:i:s');
    }

    public function getDateTimeImmutable(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->__toString());
    }
}