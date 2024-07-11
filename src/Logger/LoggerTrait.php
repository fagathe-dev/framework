<?php
namespace Fagathe\Framework\Logger;

use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\Filesystem\Filesystem;

trait LoggerTrait
{

    /**
     * @return string
     */
    public function getDate(): string
    {
        return (new DateTimeImmutable(timezone: new DateTimeZone(DEFAULT_DATE_TIMEZONE)))->format('d-m-Y');
    }

    public function getCurrentTime(): string
    {
        return (new DateTimeImmutable(timezone: new DateTimeZone(DEFAULT_DATE_TIMEZONE)))->format('d-m-Y H:i:s');
    }

    /**
     * @param string $message
     * @param string $level
     * 
     * @return string
     */
    public function formatMessage(string $message = '', string $level = 'debug'): string
    {
        return sprintf("[%s   %s] %s \n", $this->getCurrentTime(), $level, $message);
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        return new Filesystem();
    }

    /**
     * @param string $level
     * 
     * @return string
     */
    public function generateFileName(string $name = 'log'): string
    {
        $filename = explode('.', $name)[0];
        $extension = explode('.', $name)[1] ?? 'log';
        return sprintf(LOGGER_DIR . '%s-%s.%s', $filename, $this->getDate(), $extension);
    }

}