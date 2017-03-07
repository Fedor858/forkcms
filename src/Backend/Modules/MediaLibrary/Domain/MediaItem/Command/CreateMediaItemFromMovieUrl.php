<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaItem\Command;

use Backend\Modules\MediaLibrary\Domain\MediaFolder\MediaFolder;
use Backend\Modules\MediaLibrary\Domain\MediaItem\MediaItem;
use Backend\Modules\MediaLibrary\Domain\MediaItem\StorageType;

final class CreateMediaItemFromMovieUrl
{
    /** @var string */
    public $movieId;

    /** @var string */
    public $movieTitle;

    /** @var MediaFolder */
    public $mediaFolder;

    /** @var MediaItem */
    private $mediaItem;

    /** @var int */
    public $userId;

    /** @var StorageType */
    public $source;

    /**
     * CreateMediaItemFromMovieUrl constructor.
     *
     * @param StorageType $source
     * @param string $movieId
     * @param string $movieTitle
     * @param MediaFolder $mediaFolder
     * @param int $userId
     */
    public function __construct(
        StorageType $source,
        string $movieId,
        string $movieTitle,
        MediaFolder $mediaFolder,
        int $userId
    ) {
        $this->source = $source;
        $this->movieTitle = $movieTitle;
        $this->movieId = $movieId;
        $this->mediaFolder = $mediaFolder;
        $this->userId = $userId;
    }

    /**
     * @return MediaItem
     */
    public function getMediaItem(): MediaItem
    {
        return $this->mediaItem;
    }

    /**
     * @param MediaItem $mediaItem
     */
    public function setMediaItem(MediaItem $mediaItem)
    {
        $this->mediaItem = $mediaItem;
    }
}
