<?php

namespace App\Data;

class Profile extends Data
{
    const ADMIN = 'ADMINISTRATOR';

    const REPRESENTANT = 'REPRESENTANT';

    const CONSULTANT = 'CONSULTANT';

    const PRESIDENT = 'PRESIDENT';

    const ROLE_ADMIN = 'ROLE_ADMIN';

    const ROLE_REPRESENTANT = 'ROLE_REPRESENTANT';

    const ROLE_CONSULTANT = 'ROLE_CONSULTANT';

    const ROLE_PRESIDENT = 'PRESIDENT';

    const KEY_ADMIN = 'admin';

    const KEY_REPRESENTANT = 'representant';

    const KEY_CONSULTANT = 'consultant';

    const KEY_PRESIDENT = 'president';

    const LIST = [
        [
            'id' => self::ADMIN,
            'label' => 'Administrateur',
            'role' => self::ROLE_ADMIN,
            'key' => self::KEY_ADMIN,
        ],
        [
            'id' => self::REPRESENTANT,
            'label' => 'Représentant',
            'role' => self::ROLE_REPRESENTANT,
            'key' => self::KEY_REPRESENTANT,
        ],
        [
            'id' => self::CONSULTANT,
            'label' => 'Consultant',
            'role' => self::ROLE_CONSULTANT,
            'key' => self::KEY_CONSULTANT,
        ],

        [
            'id' => self::PRESIDENT,
            'label' => 'Président',
            'role' => self::ROLE_PRESIDENT,
            'key' => self::KEY_PRESIDENT,
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->list = self::LIST;
    }

    /**
     * @return list<string>
     */
    public static function getRoles(string $profileCode): array
    {
        return [self::getItem($profileCode)['role']];
    }

    public static function getKey(string $profileCode): ?string
    {
        if ($item = self::getItem($profileCode)) {
            return $item['key'];
        }

        return null;
    }
}
