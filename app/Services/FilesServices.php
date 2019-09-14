<?php

namespace FlexiCreative\Services;

use Illuminate\Support\Facades\Storage;

class FilesServices
{

    /**
     * Upload the request file
     *
     * @param $file
     * @param $name with extention
     * @param $disk
     *
     * @return string path
     */
    public function upload($directoryName, $file, $disk = 'local')
    {
        $path = '';

        if($file) {
            $name = $this->setFileName($file);
            $path = Storage::disk($disk)->putFileAs($directoryName, $file, $name,'public');

            if($disk == 's3') {
                return env('AWS_URL')."/$path";
            }
        }

        return $path;
    }

    /**
     * Attach timestamp to the original file name to make file name unique
     *
     * @param $file
     *
     * @return String $fileName
     */
    public function setFileName($file)
    {
        $fileName = time().'-'.$file->getClientOriginalName();

        return $fileName;
    }

    /**
     * Download the requested file
     *
     * @param String $filePath
     * @param String $name
     *
     * @return File
     */
    public function download(String $filePath, String $name = null)
    {
        // add an if condition to set the name
        $download = Storage::download($filePath);

        return $download;

    }

    /**
     * Get the request file size
     *
     * @param String $file
     *
     * @return $filePath
     */
    public function getFileSize($filePath)
    {
        return Storage::size($filePath);
    }

    /**
     * validate base64 image
     *
     * @param string $base64String
     *
     * @return boolean $boolean
     */
    public function validateBase64($base64String)
    {
        $image_parts = explode(';base64,', $base64String);
        if (count($image_parts) == 1) {
            return false;
        }
        $image_type_aux = explode("image/", $image_parts[0]);
        if (count($image_type_aux) == 1) {
            return false;
        }
        $image_type = $image_type_aux[1];
        $validExtensions = array('png', 'jpeg', 'jpg', 'gif');
        if (!in_array($image_type, $validExtensions)) {
            return false;
        }
        $image_data = str_replace(' ', '+', $image_parts[1]);
        $image_base64 = base64_decode($image_data);
        // If its not base64 end processing and return false
        if ($image_base64 === false) {
            return false;
        }
        return true;

    }

    /**
     * Get the file extention for a base 64 Image string
     *
     * @param string $base64String
     *
     * @return String $extention
     */
    public function base64Extention($base64String)
    {
        return  explode(";", explode("/", $base64String)[1])[0];
    }

    /**
     * Upload base64 image to Disk
     *
     * @param $file
     * @param $name with extention
     * @param $disk
     *
     * @return string path
     */
    public function uploadBase64($file, $disk = 'local')
    {
        $path = '';

        $validate = $this->validateBase64($file);

        if($validate) {

            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$file));

            $imageName = time().'-'.str_random(10).'.'. $this->base64Extention($file);

            $upload = Storage::disk($disk)->put($imageName, $image, 'public');

            $path = Storage::disk($disk)->url($imageName);


        }

        return $path;
    }
}


