<?php
declare(strict_types=1);

namespace Domain\Model;

use Domain\Exception\InvalidCellStatusEnumException;

final class CellStatus
{
    public const UNPOPULATED = 0;
    public const POPULATED = 1;

    /** @var int */
    private $value = 0;

    /**
     * @param int $value
     *
     * @throws InvalidCellStatusEnumException
     */
    public function __construct(int $value)
    {
        if (
            $value !== self::UNPOPULATED
            && $value !== self::POPULATED
        ) {
            throw new InvalidCellStatusEnumException();
        }

        $this->value = $value;
    }

    /**
     * @return int
     */
    public function __invoke()
    {
        return $this->value;
    }
}
