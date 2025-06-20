<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use File;

class AppModel extends Model
{
    use HasFactory;

    const ACTIVE = 1, INACTIVE = 2;

    public function getById($id)
    {
        return $this->find($id);
    }

    public function changeStatus($id, $value)
    {
        return $this->where($this->primaryKey, $id)->update(['status' => $value]);
    }

    // Upload a file
    public function uploadImage($file)
    {
        $imageName = null;
        if ($file) {
            $imageName = $file->getClientOriginalName();
        }
        return $imageName;
    }

    // Move a file
    public function uploadFile($file)
    {
        if ($file) {
            $name = $file->getClientOriginalName();
            $file->storeAs('public/images', $name);
        }
    }

    public function uploadVideo($file)
    {
        $imageName = null;
        if ($file) {
            $imageName = time().'.'.$file->getClientOriginalExtension();
        }
        return $imageName;
    }

    public function moveFile($file, $name, $oldName = null)
    {
        if ($file) {
            if ($oldName) {
                $dir = public_path('storage/files/' . $oldName);
                if (file_exists($dir)) {
                    File::delete($dir);
                }
            }
            
            $file->storeAs('public/files', $name);
        }
    }

    // Upload multiple image into a column
    public function uploadImages($files, $char = ';')
    {
        $imageName = null;
        $images=array();
        if($files){
            foreach($files as $file){
                $name = $file->getClientOriginalName();
                $images[] = $name;
            }
        }
        if (!empty($images) && count($images)) {
            $imageName = \implode($char, $images);
        }
        return $imageName;
    }

    // Move multiple file
    public function uploadFiles($files)
    {
        if ($files) {
            foreach($files as $file){
                $name = $file->getClientOriginalName();
                $file->storeAs('public/images', $name);
            }
        }
    }
}
