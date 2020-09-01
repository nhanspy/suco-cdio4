<?php

namespace App\Traits;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

trait ExportTrait
{
    private function backgroundColor($color)
    {
        return [
            'fillType' => Fill::FILL_SOLID,
            'color' => ['rgb' => $color],
        ];
    }

    private function font($size, $bold)
    {
        return [
            'bold' => $bold,
            'size' => $size
        ];
    }

    private function alignCenter()
    {
        return [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true,
        ];
    }

    private function borders($color)
    {
        return [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => $color],
            ],
        ];
    }

    private function richText($option = [])
    {
        $color = $option['color'] ?? '000000';
        $size = $option['size'] ?? $this->fontSize;

        return [
            'name' => 'Arial',
            'size' => $size,
            'color' => [
                'rgb' => $color
            ]
        ];
    }
}
