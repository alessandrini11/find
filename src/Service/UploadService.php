<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    private SluggerInterface $slugger;
    private ParameterBagInterface $parameter;
    private Filesystem $filesystem;

    public function __construct(SluggerInterface $slugger, ParameterBagInterface $parameter, Filesystem $filesystem)
    {
        $this->slugger = $slugger;
        $this->parameter = $parameter;
        $this->filesystem = $filesystem;
    }

    public function uploadFile(UploadedFile $file, string $directory): string
    {
        if (!$this->filesystem->exists($this->parameter->get($directory))) {
            $this->filesystem->mkdir($this->parameter->get($directory));
        }
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        // Move the file to the directory where brochures are stored
        try {
            $file->move(
                $this->parameter->get($directory),
                $newFilename
            );
        } catch (FileException $e) {
            echo $e->getMessage();
        }
        return $newFilename;
    }

    public function removeFile(string $path): void
    {
        $absolutePath = "{$this->parameter->get('kernel.project_dir')}/public/{$path}";
        $this->filesystem->remove($absolutePath);
    }
}