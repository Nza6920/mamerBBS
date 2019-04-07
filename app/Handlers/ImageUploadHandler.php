<?php
namespace App\Handlers;

use Intervention\Image\Facades\Image;

class ImageUploadHandler
{
    // 只允许以下后缀名的文件上传
    protected $allowed_ext = ["png", "jpg", "gif", "jpeg"];

    public function save($file, $folder, $file_prefix, $max_width = false)
    {
        // 获取文件的后缀名, 因图片从剪切板黏贴时后缀名为空, 此处需要确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 如果上传的不是图片文件终止操作
        if (! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 获取文件上传信息
        $fileinfo = $this->getPath($folder, $file_prefix, $extension);

        // 将图片移动到我们的目标存储路径中
        $file->move($fileinfo['upload_path'], $fileinfo['file_name']);

        // 如果限制了图片宽度，就进行裁剪
        if ($max_width && $extension != 'gif') {
            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($fileinfo['upload_path'] . '/' . $fileinfo['file_name'], $max_width);
        }

        return [
            'path' => config('app.url') . '/' . $fileinfo['folder_name'] . '/'. $fileinfo['file_name'],
        ];
    }

    public function reduceSize($file_path, $max_width)
    {
        // 先实例化, 传参是文件的磁盘物理路径
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {
            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }

    public function getPath($folder, $file_prefix, $extension)
    {
        // 构建存储的文件夹规则, 值如: uploads/images/avatars/201801/01/
        // 文件夹切割能让查找的效率更高
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        // 文件具体存储的物理路径
        $upload_path = public_path() . '/' .$folder_name;

        // 拼接文件名, 前缀增加辨析度
        $fileName = $file_prefix . '_' . time() . '_' .str_random(10) . '.' .$extension;

        return [
            'upload_path' => $upload_path,
            'file_name' => $fileName,
            'folder_name' => $folder_name,
        ];
    }
}
