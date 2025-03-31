<?php

namespace App\Data;

use App\Classes\Constant;
use Illuminate\Support\Facades\File;

class FileType extends Data
{
    const prefix = '/public/Docs/';

    const RESULT_FILES = 'result_files';

    const USER_IMAGE = 'user_image';

    const list = [
        [
            'id' => self::USER_IMAGE,
            'label' => 'user_image',
            'formats' => [
                Constant::PNG,
                Constant::JPEG,
                Constant::JPG,
            ],
            'folder_path' => self::prefix.'Users/images',
        ],
        [
            'id' => self::RESULT_FILES,
            'label' => 'result_files',
            'formats' => [
                Constant::PNG,
                Constant::JPEG,
                Constant::JPG,
                Constant::PDF,
            ],
            'folder_path' => self::prefix.'Result/files',
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->list = self::list;
    }

    /**
     * @return list<string>
     */
    public static function getFormats(string $id): array
    {
        return parent::getItem($id)['formats'];
    }

    public static function getFolderPath(string $id): string
    {
        $fileFolder = base_path().parent::getItem($id)['folder_path'];

        if (! File::isDirectory($fileFolder)) {
            File::makeDirectory($fileFolder, 0777, true, true);
        }

        return $fileFolder;
    }
}
