<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @throws Exception
     */
    public function convertToWebp (UploadedFile $picture, string $folder = ''): string
    {
        $newFileName = md5(uniqid(rand(), true)) . '.webp';

        $pictureInfos = getimagesize($picture);
        if ($pictureInfos === false) {
            throw new Exception('Format d\'image incorrect ou fichier corrompu');
        }

        $pictureSource = match ($pictureInfos['mime']) {
            'image/png' => imagecreatefrompng($picture),
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($picture),
            'image/webp' => imagecreatefromwebp($picture),
            default => throw new Exception('Format d\'image non supporté (utilisez JPEG, PNG ou WEBP)'),
        };

        $path = $this->params->get('uploads_directory') . $folder;

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        imagewebp($pictureSource, $path . '/' . $newFileName, 80);

        imagedestroy($pictureSource);

        return $newFileName;
    }
}
