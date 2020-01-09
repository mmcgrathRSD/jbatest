<?php 
namespace JBAShop\Admin\Controllers;

class Assets extends \Admin\Controllers\BaseAuth 
{
    public function index()
    {
        $view = \Dsc\System::instance()->get('theme');
    	echo $view->renderTheme('Shop/Admin/Views::assets/cloudinary.php');
    }

    public function completeUpload()
    {
        $api = new \Cloudinary\Api();

        $response = [];
        $images = (array) $this->input->get('images', null, 'array');

        for ($i=0; $i < count($images); $i++) {
            $response[$i] = [
                'filename' => $images[$i]['original_filename'],
                'thumb'    => \JBAShop\Models\Products::product_thumb($images[$i]['public_id']),
                'error'    => false
            ];

            try {
                $parts = \JBAShop\Services\Cloudinary::parseFilename($images[$i]['original_filename']);

                $api->update($images[$i]['public_id'], [
                    'tags'    => [$parts['modelNumber']],
                    'context' => [
                        'order' => $parts['order']
                    ],
                    'type'    => 'private'
                ]);

                \JBAShop\Models\Products::queueCloudinaryImageUpdate($parts['modelNumber']);
            } catch (\Exception $e) {
                $response[$i]['error'] = true;
                $api->delete_resources([$images[$i]['public_id']]);
            }
        }

        echo json_encode($response, JSON_UNESCAPED_SLASHES);
    }
}
