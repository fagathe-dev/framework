<?php
namespace Fagathe\Framework\Logs;

use DateTimeImmutable;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractLog
{
    protected ?string $level = null;
    protected string $message;
    protected DateTimeImmutable $date;
    protected Filesystem $fs;

    public function __construct() 
    {
        $this->fs = new Filesystem();
    }
    
    /**
     * getLogDir
     *
     * @return string
     */
    protected function getLogDir(): string {
        return DOCUMENT_ROOT . DIRECTORY_SEPARATOR . LOGGER_DIR;
    }
    
    /**
     * generateLogDir
     *
     * @return void
     */
    public function generateLogDir(): void {
        $dir = $this->getLogDir();
        if (!$this->fs->exists($dir)) {
            $this->fs->mkdir($dir);
        }
    }

    private function generateLogFilename() 
    {
        if ($this->level === null) {
            $this->level = "log";
        }

        $filename = $this->getLogDir() . DIRECTORY_SEPARATOR . $this->level ."";
    }
    
    /**
     * logFile
     *
     * @return void
     */
    public function logFile():void {
        $this->generateLogDir();
        $fileName = '.log';
        $file = $this->getLogDir() . DIRECTORY_SEPARATOR . $this->level;
        if (!$this->fs->exists($this->getLogDir())) {}
    }
}
