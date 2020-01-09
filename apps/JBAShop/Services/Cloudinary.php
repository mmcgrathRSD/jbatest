<?php
namespace RallyShop\Services;

class Cloudinary
{
    const PRODUCT_IMAGES = 'product_images';
    const USER_CONTENT   = 'user_content';


    /**
     * @param string $filename
     * @return array
     * @throws \Exception
     */
    public static function parseFilename($filename)
    {
        $parts = explode('_', $filename);

        if (count($parts) < 2) {
            throw new \Exception('Bad filename');
        }

        $modelNumber = strtoupper(preg_replace("/[^A-Za-z0-9]/", '', $parts[0]));
        $order = (int) $parts[1];

        if ($order <= 0 || empty($modelNumber)) {
            throw new \Exception('Bad filename');
        }

        return [
            'modelNumber' => $modelNumber,
            'order'       => $order
        ];
    }


    /**
     * @param array $fileUpload
     * @param string $folder
     * @param array $tags
     * @param array $context
     * @return array
     * @throws \Exception
     */
    public static function moveImageToCloudinary(array $fileUpload, $folder = self::USER_CONTENT, array $tags = [], array $context = [])
    {
        if (!isset($fileUpload['error']) || is_array($fileUpload['error'])) {
            throw new \Exception('Invalid Upload');
        }

        switch ($fileUpload['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \Exception('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \Exception('Exceeded filesize limit.');
            default:
                throw new \Exception('Error uploading file: ' . $fileUpload['error']);
        }

        if (empty($fileUpload['tmp_name']) || empty($fileUpload['name'])) {
            throw new \Exception('Invalid Upload Properties');
        }

        if (empty($fileUpload['size'])) {
            throw new \Exception('Invalid Upload Size');
        }

        $isImage = getimagesize($fileUpload['tmp_name']);
        if ($isImage === false) {
            throw new \Exception('Uploaded file was not an image.');
        }

        $options = [
            'folder' => $folder
        ];

        if (count($tags) > 0) {
            $options['tags'] = $tags;
        }

        if (count($context) > 0) {
            $options['context'] = $context;
        }

        $result = \Cloudinary\Uploader::upload($fileUpload['tmp_name'], $options);

        if (!isset($result['public_id'])) {
            throw new \Exception('Error moving to cloudinary.');
        }

        return $result;
    }
}
