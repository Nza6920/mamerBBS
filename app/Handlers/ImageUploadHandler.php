<?php
namespace App\Handlers;

class ImageUploadHandler
{
    // 只允许以下后缀名的文件上传
    protected $allowed_ext = ["png", "jpg", "gif", "jpeg", "bmp"];

    public function save($file, $folder, $file_prefix)
    {
        // 获取文件的后缀名, 因图片从剪切板黏贴时后缀名为空, 此处需要确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 如果上传的不是图片文件终止操作
        if (! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 构建存储的文件夹规则, 值如: uploads/images/avatars/201801/01/
        // 文件夹切割能让查找的效率更高
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        // 文件具体存储的物理路径
        $upload_path = public_path() . '/' .$folder_name;

        // 拼接文件名, 前缀增加辨析度
        $fileName = $file_prefix . '_' . time() . '_' .str_random(10) . '.' .$extension;

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $fileName);

        return [
            'path' => config('app.url') . "/$folder_name/$fileName",
        ];
    }
}
