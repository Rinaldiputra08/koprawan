<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadService
{
    private $fotoSize;
    private $imagePath;

    public function __construct()
    {
        $this->fotoSize = [100, 300];
        $this->imagePath = storage_path('app/public/images');

        if (!File::exists($this->imagePath)) {
            File::makeDirectory($this->imagePath);
        }
    }

    /**
     * @return string filename
     */
    public function uploadFoto($file, $folder = null, $size_image = null)
    {
        $imageName = time() . $file->getClientOriginalName();
        // $imageName = $file->getClientOriginalName().time().'.'.$file->extension();
        // $file->storeAs('images/profile/', $imageName);

        if (!File::exists($this->imagePath)) {
            File::makeDirectory($this->imagePath);
        }

        $destinationPath = !$folder ? storage_path('app/public/images/profile') : storage_path('app/public/images/' . $folder);
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath);
        }

        if ($size_image) {
            Image::make($file)->resize($size_image, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $imageName);
        } else {
            foreach ($this->fotoSize as $size) {
                $prefix = $size == 100 ? 'small' : 'medium';
                $img = Image::make($file)->resize($size, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $prefix . "_" . $imageName);
            }
        }

        return $imageName;
    }

    public function uploadFromBase64($image_base64, $folder, $file_name, $small = 200, $medium = 800)
    {
        $path = $this->imagePath . '/' . $folder . '/';
        if (!File::exists($path)) {
            File::makeDirectory($path);
        }

        $image = (explode(',', $image_base64))[1];
        $image_name = $file_name . '.jpg';
        File::put($path . $image_name, base64_decode($image));

        //identitas file asli
        $im_src = imagecreatefromjpeg($path . $image_name);
        $src_width = imageSX($im_src);
        $src_height = imageSY($im_src);

        $sizes = ['small_' => 200, 'medium_' => 800];

        foreach ($sizes as $key => $value) {
            $dst_width = $value;
            $dst_height = ($dst_width / $src_width) * $src_height;
            //proses perubahan ukuran
            $im = imagecreatetruecolor($dst_width, $dst_height);
            imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

            //Simpan gambar
            imagejpeg($im, $path . $key . $image_name);
            imagedestroy($im);
        }

        imagedestroy($im_src);
        // Storage::delete($path . $image_name);
        unlink($path . $image_name);

        return $image_name;
    }

    public function deleteFoto($foto, $path = "public/images/profile/", $multi_foto = true)
    {
        if ($multi_foto) {
            foreach ($this->fotoSize as $size) {
                $prefix = $size == 100 ? 'small' : 'medium';
                Storage::delete($path . $prefix . "_" . $foto);
            }
        } else {
            Storage::delete($path . $foto);
        }
    }
}
