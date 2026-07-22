<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private string $uploadsDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $this->slugger->slug($originalName)->lower();
        $newFilename = $safeName . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move($this->uploadsDirectory, $newFilename);

        return $newFilename;
    }

    public function remove(string $filename): void
    {
        $path = $this->uploadsDirectory . '/' . $filename;

        if (is_file($path)) {
            unlink($path);
        }
    }
}