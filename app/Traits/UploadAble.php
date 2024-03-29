<?php

namespace App\Traits;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Spatie\MediaLibrary\HasMedia;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Trait UploadAble
 *
 * @package App\Traits
 * @method static whereSlug(string $param)
 * @method static whereTitle($title)
 */
trait UploadAble {

    /**
     * @param Model $model
     * @param UploadedFile $file
     * @param string|null $collectionName
     * @param string $disk
     * @param array $customProperties
     */
    public function upload(
        Model $model,
        UploadedFile $file,
        string $collectionName = null,
        string $disk = 's3',
        array $customProperties = [],
    ): void {
        $model
            ->addMedia($file)
            ->preservingOriginal()
            ->storingConversionsOnDisk($disk)
            ->withCustomProperties($customProperties)
            ->toMediaCollection($collectionName ?: get_class($model), $disk);
    }

    /**
     * @param $model
     * @param UploadedFile $file
     * @param string $collectionName
     * @param array $customProperties
     * @param string $disk
     * @param array $conversions
     */
    public function addResponsiveImage(
        $model,
        UploadedFile $file,
        string $collectionName = 'default',
        array $customProperties = [],
        string $disk = 's3',
        array $conversions = Media::IMAGE_CONVERSIONS,
    ): void {
        $this->generateCustomProperties($file, $customProperties, $conversions);

        $this->upload($model, $file, $collectionName, $disk, $customProperties);
    }

    /**
     * @param $model
     * @param UploadedFile $file
     * @param string $collectionName
     * @param array $customProperties
     * @param string $disk
     * @param array $conversions
     */
    public function addResponsiveImageForChat(
        $model,
        UploadedFile $file,
        string $collectionName = 'default',
        array $customProperties = [],
        string $disk = 's3',
        array $conversions = Media::IMAGE_CONVERSIONS,
    ): void {
        $this->generateCustomProperties($file, $customProperties, $conversions);

        $model
            ->addMedia($file)
            ->preservingOriginal()
            ->storingConversionsOnDisk($disk)
            ->withCustomProperties($customProperties)
            ->toMediaCollection($collectionName ?: get_class($model), $disk);
    }

    /**
     * @param $model
     * @param array $file
     * @param string $collectionName
     * @param array $customProperties
     * @param string $disk
     */
    public function addResponsiveImageWithOrder(
        $model,
        array $file,
        string $collectionName = 'default',
        array $customProperties = [],
        string $disk = 's3'
    ): void {
        if (isset($file['file'])) {
            $this->generateCustomProperties($file['file'], $customProperties, Media::IMAGE_CONVERSIONS);

            $media = $model
                ->addMedia($file['file'])
                ->preservingOriginal()
                ->storingConversionsOnDisk($disk)
                ->withCustomProperties($customProperties)
                ->toMediaCollection($collectionName, $disk);
        } else if (isset($file['media_id'])) {
            $media = Media::find($file['media_id']);
        }

        if (isset($media)) {
            $media->order_column = $file['order'];
            $media->save();
        }
    }

    /**
     * @param $model
     * @param UploadedFile $file
     * @param string $collectionName
     * @param array $customProperties
     * @param array $customDetails
     * @param string $disk
     * @return mixed
     */
    public function addResponsiveImageWithCustomDetails(
        $model,
        UploadedFile $file,
        string $collectionName = 'default',
        array $customProperties = [],
        array $customDetails = [],
        string $disk = 's3'
    ): mixed {
        $this->generateCustomProperties($file, $customProperties, Media::IMAGE_CONVERSIONS);

        /** @var HasMedia $model */
        return $model
            ->addMedia($file)
            ->preservingOriginal()
            ->storingConversionsOnDisk($disk)
            ->withCustomProperties($customProperties)
            ->withCustomDetails($customDetails)
            ->toMediaCollection($collectionName, $disk);
    }

    /**
     * @param $file
     * @param $customProperties
     * @param $conversions
     * @return void
     */
    public function generateCustomProperties($file, &$customProperties, $conversions): void {
        [$width, $height] = $this->getImageDimensions($file);

        $this->getCustomProperties($conversions, $width, $height, $customProperties);
    }

    /**
     * @param UploadedFile $file
     * @return array
     */
    public function getImageDimensions(UploadedFile $file): array {
        $image = Image::make($file);

        return [$image->width(), $image->height()];
    }

    /**
     * @param array $conversions
     * @param $width
     * @param $height
     * @param array $customProperties
     * @return void
     */
    public function getCustomProperties(array $conversions, $width, $height, array &$customProperties): void {

        foreach ($conversions as $conversionName => $conversionWidth) {
            if ($width > $conversionWidth) {
                $newHeight = ceil($conversionWidth * $height / $width);
                $customProperties[$conversionName] = [
                    'width' => $conversionWidth,
                    'height' => (int)$newHeight,
                ];
            }
        }
    }
}
