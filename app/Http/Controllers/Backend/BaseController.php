<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use DB, Auth, View, DateTime, DateTimeZone;

use Illuminate\Support\Str;
use App\Models\Permissions;
use App\Models\Transactions;
use App\Models\Reservations;
use App\Models\SystemResCategory;
use App\Models\ReservationLeg;

use Twilio;


class BaseController extends Controller
{

    protected $auth;
    protected $authRole;
    public $permissions = [];
    public function __construct()
    {
        // $user_data = Auth::guard('admin')->user();
        // pr($user_data);exit;
      
    }
    
    /**
     * Save Image function
     *
     * @param [type] $base64img
     * @return void
     */
    public function saveImage($base64img)
    {
        if (strpos($base64img, 'data:image/jpeg;base64,') !== false) {
            $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/png;base64,') !== false) {
            $base64img = str_replace('data:image/png;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/webp;base64,') !== false) {
            $base64img = str_replace('data:image/webp;base64,', '', $base64img);
        }

        if (strpos($base64img, 'data:image/jpg;base64,') !== false) {
            $base64img = str_replace('data:image/jpg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/gif;base64,') !== false) {
            $base64img = str_replace('data:image/gif;base64,', '', $base64img);
        }
        $data = base64_decode($base64img);


        return $data;
    }

    /* For Crop Image Start */
    public function crop($file_thumb, $x, $y, $w, $h)
    {
        $targ_w = $targ_h = 550;
        $original_info = getimagesize($file_thumb);
        $type = $original_info['mime'];

        if ($type == 'image/jpeg' || $type == 'image/jpg') {
            $img_r = imagecreatefromjpeg($file_thumb);
        }
        if ($type == 'image/png') {
            $img_r = imagecreatefrompng($file_thumb);
        }
        if ($type == 'image/gif') {
            $img_r = imagecreatefromgif($file_thumb);
        }

        $dst_r = imagecreatetruecolor($targ_w, $targ_h);

        imagecopyresampled($dst_r, $img_r, 0, 0, intval($x), intval($y), $targ_w, $targ_h, intval($w), intval($h));
        header("Content-type: image/jpg");

        if ($type == 'image/jpeg' || $type == 'image/jpg') {
            imagejpeg($dst_r, $file_thumb);
        }
        if ($type == 'image/png') {
            imagepng($dst_r, $file_thumb);
        }
        if ($type == 'image/gif') {
            imagegif($dst_r, $file_thumb);
        }
    }

    public function saveImageCrop($base64img, $path, $crop_options, $thumbImageWidth = 0, $thumbImageHeight = 0)
    {
        $imagequality = 100;
        $split = explode('/', $base64img);
        $type = $split[1];
        $type = explode(";", $type);

        $v_random_image = time() . '-' . str_random(6) . '.' . $type[0];
        $tmpFile = $v_random_image;

        $base64imgOri = $base64img;

        if (strpos($base64img, 'data:image/jpeg;base64,') !== false) {
            $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/png;base64,') !== false) {
            $base64img = str_replace('data:image/png;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/webp;base64,') !== false) {
            $base64img = str_replace('data:image/webp;base64,', '', $base64img);
        }

        if (strpos($base64img, 'data:image/jpg;base64,') !== false) {
            $base64img = str_replace('data:image/jpg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/gif;base64,') !== false) {
            $base64img = str_replace('data:image/gif;base64,', '', $base64img);
        }
        $data = base64_decode($base64img);
        $file = $path . 'original/' . $tmpFile;
        file_put_contents($file, $data);

        $filename = SITE_URL . $file;

        list($width, $height) = getimagesize($filename);
        $crop_options['imageOriginalWidth'] = $width;
        $crop_options['imageOriginalHeight'] = $height;


        $info = @exif_read_data($filename);

        if (!isset($info) || $info == "") {
            $info['MimeType'] = 'image/' . $type[0];
        }

        $crop_options['top'] = (($crop_options['imageHeight'] * $thumbImageHeight) / $crop_options['height'] * $crop_options['top']) / $crop_options['imageHeight'];

        $crop_options['left'] = (($crop_options['imageWidth'] * $thumbImageWidth) / $crop_options['width'] * $crop_options['left']) / $crop_options['imageWidth'];

        $crop_options['imageWidth'] = ($crop_options['imageWidth'] * $thumbImageWidth) / $crop_options['width'];
        $crop_options['imageHeight'] = ($crop_options['imageHeight'] * $thumbImageHeight) / $crop_options['height'];
        $crop_options['height'] = $thumbImageHeight;
        $crop_options['width'] = $thumbImageWidth;


        if (isset($info['MimeType'])) {
            $file = $path . $tmpFile;
            if ($info['MimeType'] == 'image/jpeg' || $info['MimeType'] == 'image/jpg') {
                $src_img = imageCreateFromJpeg($filename);
            } elseif ($info['MimeType'] == 'image/png') {
                $src_img = imageCreateFromPng($filename);
            } elseif ($info['MimeType'] == 'image/webp') {
                $src_img = imagecreatefromwebp($filename);
            } elseif ($info['MimeType'] == 'image/gif') {
                $src_img = imageCreateFromGif($filename);
            }
            $resizedImage = imagecreatetruecolor($crop_options['imageWidth'], $crop_options['imageHeight']);
            imagecopyresampled($resizedImage, $src_img, 0, 0, 0, 0, $crop_options['imageWidth'], $crop_options['imageHeight'], $crop_options['imageOriginalWidth'], $crop_options['imageOriginalHeight']);

            $finalImage = imagecreatetruecolor($crop_options['width'], $crop_options['height']);

            imagecopyresampled($finalImage, $resizedImage, 0, 0, $crop_options['left'], $crop_options['top'], $crop_options['width'], $crop_options['height'], $crop_options['width'], $crop_options['height']);

            if ($info['MimeType'] == 'image/jpeg' || $info['MimeType'] == 'image/jpg') {
                imagejpeg($finalImage, $file, $imagequality);
            } elseif ($info['MimeType'] == 'image/png') {
                imagepng($finalImage, $file, 9);
            } elseif ($info['MimeType'] == 'image/webp') {
                imagewebp($finalImage, $file, $imagequality);
            } elseif ($info['MimeType'] == 'image/gif') {
                imagegif($finalImage, $file);
            }
        }
        return $tmpFile;
    }

    /**
     * Undocumented function
     *
     * @param [type] $base64img
     * @param [type] $tmpFile
     * @param [type] $path
     * @param [type] $crop_options
     * @param integer $thumbImageWidth
     * @param integer $thumbImageHeight
     * @return void
     */
    public function create_thumbnail($base64img, $tmpFile, $path, $crop_options, $thumbImageWidth = 0, $thumbImageHeight = 0)
    {
        $imagequality = 90;
        $split = explode('/', $base64img);
        $type = $split[1];
        $type = explode(";", $type);

        $v_random_image = time() . '-' . str_random(6) . '.' . $type[0];

        $base64imgOri = $base64img;

        if (strpos($base64img, 'data:image/jpeg;base64,') !== false) {
            $base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/png;base64,') !== false) {
            $base64img = str_replace('data:image/png;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/webp;base64,') !== false) {
            $base64img = str_replace('data:image/webp;base64,', '', $base64img);
        }

        if (strpos($base64img, 'data:image/jpg;base64,') !== false) {
            $base64img = str_replace('data:image/jpg;base64,', '', $base64img);
        }
        if (strpos($base64img, 'data:image/gif;base64,') !== false) {
            $base64img = str_replace('data:image/gif;base64,', '', $base64img);
        }
        $data = base64_decode($base64img);
        $file = $path . $tmpFile;
        file_put_contents($file, $data);

        $filename = ADMIN_URL . $file;

        list($width, $height) = getimagesize($filename);

        $info = @exif_read_data($filename);

        if (!isset($info) || $info == "") {
            $info['MimeType'] = 'image/' . $type[0];
        }

        $crop_options['top'] = (($crop_options['imageHeight'] * $thumbImageHeight) / $crop_options['height'] * $crop_options['top']) / $crop_options['imageHeight'];

        $crop_options['left'] = (($crop_options['imageWidth'] * $thumbImageWidth) / $crop_options['width'] * $crop_options['left']) / $crop_options['imageWidth'];

        $crop_options['imageWidth'] = ($crop_options['imageWidth'] * $thumbImageWidth) / $crop_options['width'];
        $crop_options['imageHeight'] = ($crop_options['imageHeight'] * $thumbImageHeight) / $crop_options['height'];
        $crop_options['height'] = $thumbImageHeight;
        $crop_options['width'] = $thumbImageWidth;
        if (isset($info['MimeType'])) {
            $file = $path . $tmpFile;
            if ($info['MimeType'] == 'image/jpeg' || $info['MimeType'] == 'image/jpg') {
                $src_img = imageCreateFromJpeg($filename);
            } elseif ($info['MimeType'] == 'image/png') {
                $src_img = imageCreateFromPng($filename);
            } elseif ($info['MimeType'] == 'image/webp') {
                $src_img = imagecreatefromwebp($filename);
            } elseif ($info['MimeType'] == 'image/gif') {
                $src_img = imageCreateFromGif($filename);
            }
            $resizedImage = imagecreatetruecolor($crop_options['imageWidth'], $crop_options['imageHeight']);
            imagecopyresampled($resizedImage, $src_img, 0, 0, 0, 0, $crop_options['imageWidth'], $crop_options['imageHeight'], $crop_options['imageOriginalWidth'], $crop_options['imageOriginalHeight']);

            $finalImage = imagecreatetruecolor($crop_options['width'], $crop_options['height']);

            imagecopyresampled($finalImage, $resizedImage, 0, 0, $crop_options['left'], $crop_options['top'], $crop_options['width'], $crop_options['height'], $crop_options['width'], $crop_options['height']);

            if ($info['MimeType'] == 'image/jpeg' || $info['MimeType'] == 'image/jpg') {
                imagejpeg($finalImage, $file, $imagequality);
            } elseif ($info['MimeType'] == 'image/png') {
                imagepng($finalImage, $file, 9);
            } elseif ($info['MimeType'] == 'image/webp') {
                imagewebp($finalImage, $file, $imagequality);
            } elseif ($info['MimeType'] == 'image/gif') {
                imagegif($finalImage, $file);
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $base64img
     * @param [type] $x
     * @param [type] $y
     * @param [type] $w
     * @param [type] $h
     * @param [type] $path
     * @param [type] $thumb_path
     * @return void
     */
    public function cropImages($base64img, $x, $y, $w, $h, $path, $thumb_path)
    {

        $v_random_image = time() . '-' . Str::random(6);

        $base64img = substr(strstr($base64img, ','), 1);
        $tmpFile = $v_random_image . '.png';

        $targ_w = $targ_h = 150;
        $jpeg_quality = 90;
        $img_src = base64_decode($base64img);
        $file = $path . $tmpFile;
        file_put_contents($file, $img_src);

        $img_r = imagecreatefromstring($img_src);
        $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
        imagecopyresampled($dst_r, $img_r, 0, 0, $x, $y, $targ_w, $targ_h, $w, $h);
        //header('Content-type: image/png');
        ob_start();

        imagejpeg($dst_r, null, $jpeg_quality);
        $image_data = ob_get_contents();
        ob_end_clean();
        $fileThumb = $thumb_path . $tmpFile;


        file_put_contents($fileThumb, $image_data);

        return $tmpFile;
    }

    public function fetchLatLng($request, $address = '')
    {

        // pr($address); exit;
        $inputedfullAddress = '';
        if ($address != '') {
            $inputedfullAddress = strtolower($address);
        }

        $return_array = array();

        $full_address = strtolower($inputedfullAddress);
        if ($full_address != '') {
            $fullAddress = urlencode($full_address);

            // &key='.GOOGLE_KEY
            //  $url = 'https://maps.google.com/maps/api/geocode/xml?address=' . $fullAddress . '&sensor=false&
            // key='.GOOGLE_KEY_PICKER;
            $url = 'https://maps.google.com/maps/api/geocode/xml?address=' . $fullAddress . '&key=' . GOOGLE_MAP_KEY;
            $xml = simplexml_load_file($url);
            $arr = json_decode(json_encode($xml), 1);

            if ($arr['status'] == 'OK') {
                if (isset($arr['result'][0])) {
                    $latitude = $arr['result'][0]['geometry']['location']['lat'];
                    $longitude = $arr['result'][0]['geometry']['location']['lng'];
                    $place_id = $arr['result']['place_id'];
                } else {
                    $latitude = $arr['result']['geometry']['location']['lat'];
                    $longitude = $arr['result']['geometry']['location']['lng'];
                    $place_id = $arr['result']['place_id'];
                }

                $return_array['latitude'] = $latitude;
                $return_array['longitude'] = $longitude;
                $return_array['place_id'] = $place_id;
            }
        } else {
            $return_array['latitude'] = '';
            $return_array['longitude'] = '';
            $return_array['place_id'] = '';
        }

        // pr($return_array);
        return $return_array;
    }
  
}
