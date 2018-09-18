<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    private $defaultFallback;

    public function __construct($targetDirectory, $defaultFallback)
    {
        $this->targetDirectory = $targetDirectory;
        $this->defaultFallback = $defaultFallback;
    }

    public function upload(?UploadedFile $file, string $fallback = '')
    {
        $fileName = $fallback != '' ? $fallback : $this->getDefaultFallback();
        if (!is_null($file) && $file->isValid())
        {
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getTargetDirectory(), $fileName);
        }
        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function getDefaultFallback()
    {
        return $this->defaultFallback;
    }
}