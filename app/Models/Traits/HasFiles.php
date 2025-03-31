<?php

namespace App\Models\Traits;

use App\Classes\Constant;
use App\Data\FileType;
use App\Exceptions\ModelException;
use App\Helper\Tools;

trait HasFiles
{
    /**
     * @param  array<int,mixed>  $files
     * @param  array<int,string>  $allowedExtensions
     *
     * @throws ModelException
     */
    public function addFiles(array $files, string $fileType, array $allowedExtensions = Constant::ALLOWED_FILE_EXTENSIONS, string $column = 'files', bool $persist = false): self
    {
        if ($this->$column != null) {
            if (is_array($this->$column)) {
                $_files = $this->$column;
            } else {
                $_files = json_decode($this->$column) ?? [];
            }
        } else {
            $_files = [];
        }

        foreach ($files as $file) {
            if (in_array($file->extension(), $allowedExtensions)) {
                $fileName = Tools::buildFileName($fileType, $file->extension());
                $path = FileType::getFolderPath($fileType);
                $file->move($path, $fileName);
                $_files[] = $fileName;
            }
        }

        $this->$column = $_files;

        if ($persist) {
            $this->save();
        }

        return $this;
    }

    /**
     * @param  mixed  $file
     * @param  array<int,string>  $allowedExtensions
     *
     * @throws ModelException
     */
    public function addFile($file, string $fileType, array $allowedExtensions = Constant::ALLOWED_FILE_EXTENSIONS, string $column = 'files', bool $persist = false): self
    {
        $files = [$file];

        return self::addFiles($files, $fileType, allowedExtensions: $allowedExtensions, column: $column, persist: $persist);
    }

    /**
     * @param  mixed  $file
     * @param  array<int,string>  $allowedExtensions
     *
     * @throws ModelException
     */
    public function addSimpleFile($file, string $fileType, array $allowedExtensions = Constant::ALLOWED_FILE_EXTENSIONS, string $column = 'image', bool $persist = false): self
    {
        if (in_array($file->extension(), $allowedExtensions)) {
            $fileName = Tools::buildFileName($fileType, $file->extension());
            $path = FileType::getFolderPath($fileType);

            $file->move($path, $fileName);

            $this->$column = $fileName;

            if ($persist) {
                $this->save();
            }
        }

        return $this;
    }
}
