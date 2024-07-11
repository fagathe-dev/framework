<?php
namespace Fagathe\Framework\Logger;

use Fagathe\Framework\Logger\LoggerTrait;
use Symfony\Component\Filesystem\Filesystem;

final class Logger
{
    use LoggerTrait;

    private static $levels = ['info', 'debug', 'error'];
    private Filesystem $fs;

    public function __construct(private string $filePath = '')
    {
        $this->fs = $this->getFilesystem();
    }

    /**
     * @param string $message
     * @param string $level
     * 
     * @return void
     */
    private function log(string $message, string $level = 'notice'): void
    {
        $fileName = $this->generateFileName($this->filePath !== '' ? $this->filePath : $level);
        $fileEnvName = $this->generateFileName(APP_ENV);
        $formattedMessage = $this->formatMessage($message, $level);

        if ($this->fs->exists(LOGGER_DIR)) {
            $this->fs->mkdir(LOGGER_DIR);
        }

        if (!$this->fs->exists($fileName)) {
            $this->fs->mkdir(dirname($fileName));
            $this->fs->touch($fileName);
        }

        if ($this->fs->exists($fileEnvName)) {
            $this->fs->touch($fileEnvName);
        }

        $this->fs->appendToFile($fileName, $formattedMessage);
        $this->fs->appendToFile($fileEnvName, $formattedMessage);
    }


    /**
     * @param mixed $message
     * 
     * @return void
     */
    public function info($message): void
    {
        $this->log($message, 'info');
    }

    /**
     * @param mixed $message
     * 
     * @return void
     */
    public function debug($message): void
    {
        $this->log($message, 'debug');
    }

    /**
     * @param mixed $message
     * 
     * @return void
     */
    public function warning($message): void
    {
        $this->log($message, 'warning');
    }


    /**
     * @param mixed $message
     * 
     * @return void
     */
    public function error($message): void
    {
        $this->log($message, 'error');
    }

}