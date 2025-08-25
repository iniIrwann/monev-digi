<?php

namespace App\Exports;

use App\Models\Bidang;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DRKExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell
{
    protected $desa;

    public function __construct()
    {
        $this->desa = Auth::user()->desa ?? 'DESA TIDAK DIKETAHUI';
    }

    private function splitKode($kode)
    {
        if (!$kode) {
            return ['', '', ''];
        }
        $parts = preg_split('/[.\s]/', $kode);
        return [
            $parts[0] ?? '',
            $parts[1] ?? '',
            $parts[2] ?? '',
        ];
    }

    public function collection()
    {
        $rows = [];
        $no = 1;

        $bidangs = Bidang::with([
            'kegiatans.subkegiatans.targets.realisasis'
        ])->get();

        foreach ($bidangs as $bidang) {
            $kode = $this->splitKode($bidang->kode_rekening);

            // baris Bidang
            $rows[] = [
                $no,
                $kode[0], '', '',
                strtoupper($bidang->nama_bidang),
                '', '', '', '', '', '', '', '', '', '', ''
            ];

            foreach ($bidang->kegiatans as $kegiatan) {
                $kode = $this->splitKode($kegiatan->kode_rekening);

                $rows[] = [
                    '', // no kosong karena ikut bidang
                    $kode[0], $kode[1], '',
                    strtoupper($kegiatan->nama_kegiatan),
                    '', '', '', '', '', '', '', '', '', '', ''
                ];

                foreach ($kegiatan->subkegiatans as $sub) {
                    foreach ($sub->targets as $target) {
                        $tahap1 = $target->realisasis->where('tahap', 1)->first();
                        $tahap2 = $target->realisasis->where('tahap', 2)->first();
                        $kode = $this->splitKode($sub->kode_rekening);

                        $total = ($tahap1->realisasi_keuangan ?? 0) + ($tahap2->realisasi_keuangan ?? 0);

                        $rows[] = [
                            '', // no kosong
                            $kode[0], $kode[1], $kode[2],
                            strtoupper($sub->nama_subkegiatan),
                            $target->volume_keluaran ?? '',
                            $target->uraian_keluaran ?? '',
                            $target->anggaran_target ?? '',
                            $tahap1->volume_keluaran ?? '',
                            $tahap1->uraian_keluaran ?? '',
                            $tahap1->realisasi_keuangan ?? '',
                            $tahap2->volume_keluaran ?? '',
                            $tahap2->uraian_keluaran ?? '',
                            $tahap2->realisasi_keuangan ?? '',
                            $total, // ✅ TOTAL BIAYA (Rp)
                        ];
                    }
                }
            }
            $no++;
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            [
                'NO',
                'KODE REKENING', '', '',
                'RENCANA KEGIATAN',
                'VOLUME', 'Uraian',
                'JUMLAH BIAYA (Rp)',
                'TAHAP I', '', '',
                'TAHAP II', '', '',
                'TOTAL BIAYA (Rp)', // ✅ header baru
            ],
            [
                '', 'A', '1', '1', '',
                'VOL', 'Uraian',
                'Jumlah',
                'VOL', 'Uraian', 'JUMLAH BIAYA (Rp)',
                'VOL', 'Uraian', 'JUMLAH BIAYA (Rp)',
                'Jumlah', // ✅ subheader baru
            ]
        ];
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function styles(Worksheet $sheet)
    {
        // Judul atas
        $sheet->mergeCells('A1:O1')->setCellValue('A1', 'DAFTAR RINCIAN KEGIATAN ( DRK )');
        $sheet->mergeCells('A2:O2')->setCellValue('A2', 'DANA DESA');
        $sheet->mergeCells('A3:O3')->setCellValue('A3', 'DESA ' . strtoupper($this->desa) . ' KECAMATAN SOREANG KABUPATEN BANDUNG');
        $sheet->mergeCells('A4:O4')->setCellValue('A4', 'TAHUN ANGGARAN 2025');

        $sheet->getStyle('A1:A4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);

        // Merge header
        $sheet->mergeCells('A6:A7'); // NO
        $sheet->mergeCells('B6:D6'); // KODE REKENING
        $sheet->mergeCells('E6:E7'); // Rencana kegiatan
        $sheet->mergeCells('F6:G6'); // Volume
        $sheet->mergeCells('H6:H7'); // Jumlah biaya (target)
        $sheet->mergeCells('I6:K6'); // Tahap I
        $sheet->mergeCells('L6:N6'); // Tahap II
        $sheet->mergeCells('O6:O7'); // ✅ Total biaya

        // Style header
        $sheet->getStyle('A6:O7')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FF92D050'],
            ],
            'borders' => ['allBorders' => ['borderStyle' => 'thin']],
        ]);

        // Style isi tabel
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("A6:O{$highestRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => 'thin']],
            'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
        ]);

        // Kolom kegiatan rata kiri
        $sheet->getStyle("E8:E{$highestRow}")->getAlignment()->setHorizontal('left');

        // Auto width semua kolom
        foreach (range('A', 'O') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }
}
