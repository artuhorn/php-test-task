<?php
namespace App;

use RuntimeException;

class Uploader
{
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function upload()
    {
        $file = $this->file;
        if ($file['tmp_name']) {
            $extension = $this->getFileExtension($file['tmp_name']);
            
            if ($extension !== false) {
                $filename = $file['tmp_name'];

                if (is_uploaded_file($filename)) {
                    // Перемещаем файл в папку с изображениями, генерируя новое уникальное имя
                    $newFilename = sha1_file($filename);
                    if (move_uploaded_file($filename, sprintf('./upload/%s.%s', $newFilename, $extension))) {
                        return $newFilename . '.' . $extension;
                    } else {
                        throw new RuntimeException('Не удалось переместить загруженный файл');
                    }
                }
            }
        }

        return '';
    }

    /**
     * Возвращает false если расширение файла не находится в списке разрешенных
     * В противном случае возвращает расширение файла
     * @param string $filename
     * @return bool|string
     */
    protected function getFileExtension(string $filename)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return array_search(
            finfo_file($finfo, $filename),
            [
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ]
        );
    }
}
