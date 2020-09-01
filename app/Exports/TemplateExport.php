<?php

namespace App\Exports;

use App\Entities\Project;
use App\Repositories\TranslationRepository;
use App\Traits\ExportTrait;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class TemplateExport implements WithHeadings, WithEvents, WithCustomStartCell, FromQuery, WithMapping
{
    use ExportTrait;

    /** @var Worksheet */
    private $sheet;

    private $fontSize;

    private $font;

    private $startCell;

    private $projectName;

    private $projectId;


    /** @var TranslationRepository */
    private $translationRepo;

    public function __construct(Project $project = null)
    {
        $this->sheet = null;
        $this->fontSize = config('excel.style.font_size', 10);
        $this->font = config('excel.style.font', 'Arial');
        $this->startCell = config('excel.sheet.start_cell', 'A1');
        $this->projectName = $project->name ?? config('excel.sheet.name');
        $this->projectId = $project->id ?? 0;
        $this->translationRepo = app(TranslationRepository::class);
    }

    public function query()
    {
        return $this->translationRepo->where(['project_id' => $this->projectId])->model;
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->phrase,
            $row->meaning,
            $row->description
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // init $sheet
                $this->sheet = $event->getSheet()->getDelegate();

                // Common setting
                $this->sheet->setTitle($this->projectName);
                $this->sheet->getParent()->getDefaultStyle()->getFont()->setName($this->font);
                $this->sheet->getParent()->getDefaultStyle()->getFont()->setSize($this->fontSize);
                $this->sheet->getParent()->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
                $this->sheet->getParent()->getDefaultStyle()->getAlignment()->setWrapText(true);

                // Edit Column Width & Height
                $this->setColWidth('A', 15);
                $this->setColWidth('B', 40);
                $this->setColWidth('C', 60);
                $this->setColWidth('D', 60);
                $this->setColWidth('E', 18);
                $this->setColWidth('F', 18);
                $this->setColWidth('G', 18);
                $this->setColWidth('H', 18);
                $this->setRowHeight(2, 40);
                $this->setRowHeight(3, 40);
                $this->setRowHeight(4, 40);
                $this->setRowHeight(5, 40);
                $this->styleBorder('E1:H4');

                // Table legends
                $this->sheet->mergeCells('E1:H1');
                $this->sheet->mergeCells('F2:H2');
                $this->sheet->mergeCells('F3:H3');
                $this->sheet->mergeCells('F4:H4');

                // Table Legend Header
                $this->setValue('E1', 'Legends');
                $this->styleHeader('E1', [
                    'background_color' => 'fdff00',
                    'bold' => true
                ]);
                $this->styleHeader('E2');
                $this->styleHeader('E3');
                $this->styleHeader('E4');

                // Table Legend Text
                $wordNote = $this->createRichText([
                    "Word\n" => $this->richText(),
                    '(REQUIRED)' => $this->richText(['color' => 'f51f09'])
                ]);
                $meaningNote = $this->createRichText([
                    "Meaning\n" => $this->richText(),
                    '(REQUIRED)' => $this->richText(['color' => 'f51f09'])
                ]);
                $descriptionNote = $this->createRichText([
                    "Description\n" => $this->richText(),
                    '(NO REQUIRED)' => $this->richText(['color' => 'f51f09'])
                ]);
                $text = $this->createRichText([
                    "- Nhập Nghĩa của từ cần dịch\n" => [],
                    "- Số lượng kí tự không được quá 65,535 ký tự\n" => [],
                    "- Không giới hạn kí tự đặc biệt" => [],
                ]);
                $this->setValue('E2', $wordNote);
                $this->setValue('E3', $meaningNote);
                $this->setValue('E4', $descriptionNote);
                $this->setValue('F2', $text);
                $this->setValue('F3', $text);
                $this->setValue('F4', $text);

                // Edit Main Table Header Style
                $this->styleHeader('A5:D5', [
                    'background_color' => '6aa84e',
                    'font_size' => 14,
                    'bold' => true
                ]);

                // Main Table Body Style
                $this->styleBorder('A5:D100');
                $this->styleHeader('A6:D100');

                // freeze row 5
                $this->sheet->freezePane('A6');
            }
        ];
    }

    private function setColWidth($column, $width)
    {
        if ($this->sheet) {
            $this->sheet->getColumnDimension($column)->setWidth($width);
        }
    }

    private function setRowHeight($row, $width)
    {
        if ($this->sheet) {
            $this->sheet->getRowDimension($row)->setRowHeight($width);
        }
    }

    private function setValue($cell, $value)
    {
        if ($this->sheet) {
            $this->sheet->getCell($cell)->setValue($value);
        }
    }

    private function createRichText(array $texts)
    {
        $richText = new RichText();

        foreach ($texts as $text => $options) {
            $tempText = $richText ->createTextRun($text);
            $tempText->getFont()->applyFromArray($options);
        }

        return $richText;
    }

    private function styleHeader($cell, $options = [])
    {
        if ($this->sheet) {
            $backgroundColor = $options['background_color'] ?? 'ffffff';
            $fontSize = $options['font_size'] ?? $this->fontSize;
            $bold = $options['bold'] ?? false;

            $this->sheet->getStyle($cell)->applyFromArray([
                'fill' => $this->backgroundColor($backgroundColor),
                'font' => $this->font($fontSize, $bold),
                'alignment' => $this->alignCenter()
            ]);
        }
    }

    private function styleBorder($cell, $color = '000000')
    {
        if ($this->sheet) {
            $this->sheet->getStyle($cell)->applyFromArray([
                'borders' => $this->borders($color)
            ]);
        }
    }

    public function startCell(): string
    {
        return $this->startCell;
    }

    public function headings(): array
    {
        return [
            'STT',
            'Word',
            'Meaning',
            'Description'
        ];
    }
}
