<?php

namespace SymfonyWP\Entity;

use Doctrine\ORM\Mapping as ORM;
use SymfonyWP\Repositories\AttachmentRepository;

#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
#[ORM\DiscriminatorValue('attachment')]
class Attachment extends Post
{
    public function getAttachedFileRelativePath(): ?string
    {
        return $this->getMetaValue('_wp_attached_file');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getMetadata(): ?array
    {
        $meta = $this->getFirstPostMetaWithKey('_wp_attachment_metadata');
        if ($meta === null) {
            return null;
        }

        $value = $meta->getUnserializedValue();

        return is_array($value) ? $value : null;
    }

    public function getWidth(): ?int
    {
        return $this->getMetadataIntValue('width');
    }

    public function getHeight(): ?int
    {
        return $this->getMetadataIntValue('height');
    }

    public function getFilesize(): ?int
    {
        return $this->getMetadataIntValue('filesize');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getSizes(): ?array
    {
        return $this->getMetadataArrayValue('sizes');
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getImageMeta(): ?array
    {
        return $this->getMetadataArrayValue('image_meta');
    }

    public function getOriginalImage(): ?string
    {
        $value = $this->getMetadataValue('original_image');

        return is_string($value) ? $value : null;
    }

    public function getMetadataValue(string $key): mixed
    {
        $metadata = $this->getMetadata();

        return $metadata[$key] ?? null;
    }

    private function getMetaValue(string $key): ?string
    {
        $meta = $this->getFirstPostMetaWithKey($key);

        return $meta?->getValue();
    }

    private function getMetadataIntValue(string $key): ?int
    {
        $value = $this->getMetadataValue($key);

        return is_int($value) ? $value : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function getMetadataArrayValue(string $key): ?array
    {
        $value = $this->getMetadataValue($key);

        return is_array($value) ? $value : null;
    }
}
