<?php

namespace SymfonyWP;

use SymfonyWP\Entity\Post;

class AttachmentPathResolver
{
    public function __construct(private readonly string $wpInstallationPath)
    {
    }

    public function getFullSizeImagePath(Post $attachment): ?string
    {
        $relativePath = $attachment->getAttachedFileRelativePath();
        if ($relativePath === null) {
            return null;
        }

        $originalImage = $attachment->getOriginalImage();
        if ($originalImage !== null) {
            $relativePath = $this->replaceFilename($relativePath, $originalImage);
        }

        return $this->buildAbsoluteUploadPath($relativePath);
    }

    public function getImagePathForSize(Post $attachment, string $size): ?string
    {
        $relativePath = $attachment->getAttachedFileRelativePath();
        $sizes = $attachment->getSizes();

        if ($relativePath === null || $sizes === null) {
            return null;
        }

        $sizeConfiguration = $sizes[$size] ?? null;
        if (!is_array($sizeConfiguration)) {
            return null;
        }

        $filename = $sizeConfiguration['file'] ?? null;
        if (!is_string($filename) || $filename === '') {
            return null;
        }

        return $this->buildAbsoluteUploadPath($this->replaceFilename($relativePath, $filename));
    }

    /**
     * @return array<string, string>
     */
    public function getAllRegisteredSizeImagePaths(Post $attachment): array
    {
        $relativePath = $attachment->getAttachedFileRelativePath();
        $sizes = $attachment->getSizes();

        if ($relativePath === null || $sizes === null) {
            return [];
        }

        $paths = [];
        foreach ($sizes as $size => $sizeConfiguration) {
            if (!is_string($size) || !is_array($sizeConfiguration)) {
                continue;
            }

            $filename = $sizeConfiguration['file'] ?? null;
            if (!is_string($filename) || $filename === '') {
                continue;
            }

            $paths[$size] = $this->buildAbsoluteUploadPath($this->replaceFilename($relativePath, $filename));
        }

        return $paths;
    }

    private function buildAbsoluteUploadPath(string $relativePath): string
    {
        return rtrim($this->wpInstallationPath, '/\\') . '/wp-content/uploads/' . ltrim($relativePath, '/\\');
    }

    private function replaceFilename(string $path, string $filename): string
    {
        $directory = dirname($path);

        if ($directory === '.' || $directory === '') {
            return $filename;
        }

        return rtrim($directory, '/\\') . '/' . ltrim($filename, '/\\');
    }
}
