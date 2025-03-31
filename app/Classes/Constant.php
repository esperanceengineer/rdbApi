<?php

namespace App\Classes;

abstract class Constant
{
    const PDF = 'pdf';

    const JPG = 'jpg';

    const PNG = 'png';

    const JPEG = 'jpeg';

    const ENCRYPTION = 'GP-RH@2024';

    const PER_PAGE = 10;

    const ALLOWED_FILE_EXTENSIONS = [self::PDF, self::PNG, self::JPG, self::JPEG];

    const DATE_FORMAT_YMD = 'Y-m-d';

    const DATE_FORMAT_YMD_HIS = 'Y-m-d H:i:s';
}
