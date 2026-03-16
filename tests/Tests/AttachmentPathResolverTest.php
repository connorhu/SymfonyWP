<?php

namespace SymfonyWP\Tests;

use PHPUnit\Framework\TestCase;
use SymfonyWP\AttachmentPathResolver;
use SymfonyWP\Entity\Post;

class AttachmentPathResolverTest extends TestCase
{
    public function testGetFullSizeImagePathFallsBackToAttachedRelativePath(): void
    {
        $attachment = $this->createConfiguredMock(Post::class, [
            'getAttachedFileRelativePath' => '2024/05/image-scaled.jpg',
            'getOriginalImage' => null,
        ]);

        $resolver = new AttachmentPathResolver('/var/www/html');

        $this->assertSame(
            '/var/www/html/wp-content/uploads/2024/05/image-scaled.jpg',
            $resolver->getFullSizeImagePath($attachment)
        );
    }

    public function testGetFullSizeImagePathUsesOriginalImageWhenAvailable(): void
    {
        $attachment = $this->createConfiguredMock(Post::class, [
            'getAttachedFileRelativePath' => '2024/05/image-scaled.jpg',
            'getOriginalImage' => 'image.jpg',
        ]);

        $resolver = new AttachmentPathResolver('/var/www/html/');

        $this->assertSame(
            '/var/www/html/wp-content/uploads/2024/05/image.jpg',
            $resolver->getFullSizeImagePath($attachment)
        );
    }

    public function testGetImagePathForSize(): void
    {
        $attachment = $this->createConfiguredMock(Post::class, [
            'getAttachedFileRelativePath' => '2024/05/image.jpg',
            'getSizes' => [
                'thumbnail' => ['file' => 'image-150x150.jpg'],
            ],
        ]);

        $resolver = new AttachmentPathResolver('/var/www/html');

        $this->assertSame(
            '/var/www/html/wp-content/uploads/2024/05/image-150x150.jpg',
            $resolver->getImagePathForSize($attachment, 'thumbnail')
        );
        $this->assertNull($resolver->getImagePathForSize($attachment, 'medium'));
    }

    public function testGetAllRegisteredSizeImagePathsReturnsOnlyValidSizes(): void
    {
        $attachment = $this->createConfiguredMock(Post::class, [
            'getAttachedFileRelativePath' => '2024/05/image.jpg',
            'getSizes' => [
                'thumbnail' => ['file' => 'image-150x150.jpg'],
                'large' => ['file' => 'image-1024x1024.jpg'],
                'broken' => ['height' => 100],
            ],
        ]);

        $resolver = new AttachmentPathResolver('/var/www/html');

        $this->assertSame([
            'thumbnail' => '/var/www/html/wp-content/uploads/2024/05/image-150x150.jpg',
            'large' => '/var/www/html/wp-content/uploads/2024/05/image-1024x1024.jpg',
        ], $resolver->getAllRegisteredSizeImagePaths($attachment));
    }
}
