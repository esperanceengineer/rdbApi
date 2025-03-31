<?php

namespace App\Data;

use App\Exceptions\ModelException;

class ExceptionCode extends Data
{
    const LIST = [
        [
            'id' => ModelException::AttributeNotFoundException,
            'label' => "L'attribut [resource] n'a pas été trouvé",
        ],
        [
            'id' => ModelException::ModelNotFoundException,
            'label' => "Aucun(e) [resource] n'a pas été trouvé(e)",
        ],
        [
            'id' => ModelException::ModelNotDeletedException,
            'label' => "[resource] n'a pas pu être supprimé(e)",
        ],
        [
            'id' => ModelException::ModelNotEditedException,
            'label' => "[resource] n'a pas pu être [action](e)",
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->list = self::LIST;
    }
}
