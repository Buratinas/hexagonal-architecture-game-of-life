<?php
declare(strict_types=1);

namespace Domain\Model\PopulateStrategies;

use Domain\Model\Cell;
use Domain\Model\CellStatus;
use Domain\Model\Coordinate;

final class RandomPopulateStrategy implements PopulateStrategyInterface
{
    /**
     * @param array $boardGrid
     *
     * @return array
     */
    public function populate(array $boardGrid): array
    {
        for ($y = 0; $y < count($boardGrid); $y++) {
            $boardGrid = $this->populateRow($boardGrid, $y);
        }

        return $boardGrid;
    }

    /**
     * @param array $grid
     * @param       $rowIndex
     *
     * @return array
     */
    private function populateRow(array $grid, $rowIndex): array
    {
        for ($x = 0; $x < count($grid[$rowIndex]); $x++) {
            $grid = $this->populateCell($grid, $rowIndex, $x);
        }

        return $grid;
    }

    /**
     * @param array $grid
     * @param       $rowIndex
     * @param       $cellIndex
     *
     * @return array
     */
    private function populateCell(array $grid, $rowIndex, $cellIndex): array
    {
        $value = rand(0,1);

        $coordinate = new Coordinate($cellIndex, $rowIndex);
        $cellStatus = new CellStatus($value);

        $grid[$rowIndex][$cellIndex] = new Cell($coordinate, $cellStatus);

        return $grid;
    }
}
