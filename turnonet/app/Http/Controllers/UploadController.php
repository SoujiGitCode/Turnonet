<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller {

    /**
     * Upload image
     * @param Request $request
     * @return type
     */
    public function postUpload(Request $request) {
        try {
            $path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
            $pic_name = $_FILES['file']['name'];
            $temp = $_FILES['file']['tmp_name'];
            $upload = $path . $pic_name;
            move_uploaded_file($temp, $upload);
            $name = explode('.', $pic_name);
            $name = strtolower($name[0]);
            $name = mb_strtolower($name, 'UTF-8');
            $name = str_replace('á', 'a', $name);
            $name = str_replace('é', 'e', $name);
            $name = str_replace('í', 'i', $name);
            $name = str_replace('ó', 'o', $name);
            $name = str_replace('ú;', 'u', $name);
            $name = str_replace('ñ', 'n', $name);
            $name = preg_replace('([^A-Za-z0-9])', '', $name);
            $name = str_replace('-', '_', $name);
            $name = str_replace(' ', '_', $name);
            $type = strtolower(strrchr($pic_name, "."));
            $pic_new = $name . "_" . time() . $type;
            $upload_new = $path . $pic_new;
            rename($upload, $upload_new);
            return response()->json(["photo" => $pic_new]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Upload image
     * @param Request $request
     * @return type
     */
    public function upload_thumb(Request $request) {
        try {
            $path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
            $pic_name = $_FILES['file']['name'];
            $temp = $_FILES['file']['tmp_name'];
            $upload = $path . $pic_name;
            move_uploaded_file($temp, $upload);
            $name = explode('.', $pic_name);
            $name = strtolower($name[0]);
            $name = mb_strtolower($name, 'UTF-8');
            $name = str_replace('á', 'a', $name);
            $name = str_replace('é', 'e', $name);
            $name = str_replace('í', 'i', $name);
            $name = str_replace('ó', 'o', $name);
            $name = str_replace('ú;', 'u', $name);
            $name = str_replace('ñ', 'n', $name);
            $name = preg_replace('([^A-Za-z0-9])', '', $name);
            $name = str_replace('-', '_', $name);
            $name = str_replace(' ', '_', $name);


            $type = strtolower(strrchr($pic_name, "."));
            $pic_new = $name . "_" . time() . $type;
            $upload_new = $path . $pic_new;
            rename($upload, $upload_new);

       



            return response()->json(["photo" => $pic_new]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    /**
     * Upload image
     * @param Request $request
     * @return type
     */
    public function upload_docs(Request $request) {
        try {
            $path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
            $pic_name = $_FILES['file']['name'];
            $temp = $_FILES['file']['tmp_name'];
            $upload = $path . $pic_name;
            move_uploaded_file($temp, $upload);
            $name = explode('.', $pic_name);
            $name = strtolower($name[0]);
            $name = mb_strtolower($name, 'UTF-8');
            $name = str_replace('á', 'a', $name);
            $name = str_replace('é', 'e', $name);
            $name = str_replace('í', 'i', $name);
            $name = str_replace('ó', 'o', $name);
            $name = str_replace('ú;', 'u', $name);
            $name = str_replace('ñ', 'n', $name);
            $name = preg_replace('([^A-Za-z0-9])', '', $name);
            $name = str_replace('-', '_', $name);
            $name = str_replace(' ', '_', $name);
            $type = strtolower(strrchr($pic_name, "."));
            $pic_new = $name . "_" . time() . $type;
            $upload_new = $path . $pic_new;
            rename($upload, $upload_new);
            $file_size = $this->FileSizeConvert(filesize($upload_new));
            return response()->json(["photo" => $pic_new, "file_size" => $file_size]);
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function FileSizeConvert($bytes) {
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem["VALUE"]) {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", ",", strval(round($result, 2))) . " " . $arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

}
