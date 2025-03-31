<?php

namespace App\Data;

use App\Pattern\Singleton;

class Data extends Singleton
{
    /** @phpstan-ignore-next-line */
    protected array $list = [];

    public function __construct()
    {
        parent::__construct();
    }

    public static function exist(mixed $id): bool
    {
        /** @var Data */
        $self = self::getInstance();

        return in_array($id, array_column($self->list(), 'id'));
    }

    /** @phpstan-ignore-next-line */
    protected function list(): array
    {
        return $this->list;
    }

    public static function getLabel(mixed $id): ?string
    {
        if ($item = self::getItem($id)) {
            return $item['label'];
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    public static function getLabels(): ?array
    {
        /** @var Data */
        $self = self::getInstance();

        $list = $self->list();
        foreach ($list as $key => $value) {
            $list[$key] = $value['label'];
        }

        return $list;
    }

    /**
     * @return array<string, string>
     */
    public static function getCodes(): ?array
    {
        /** @var Data */
        $self = self::getInstance();
        $list = $self->list();
        foreach ($list as $key => $value) {
            $list[$key] = $value['id'];
        }

        return $list;
    }

    /** @phpstan-ignore-next-line */
    public static function getItem(mixed $id): ?array
    {
        if (empty($id)) {
            return null;
        }

        /** @var Data */
        $self = self::getInstance();
        $list = $self->list();

        foreach ($list as $item) {
            if (strtolower($item['id']) == strtolower($id)) {
                return $item;
            }
        }

        return null;
    }

    /** @phpstan-ignore-next-line */
    public static function all(): array
    {
        /** @var Data */
        $self = self::getInstance();

        return $self->list();
    }
}
