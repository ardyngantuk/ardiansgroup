<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use XMLReader;

class ImportExcel
{
    public function run(string $file): void
    {
        if (!file_exists($file)) {
            throw new \RuntimeException("File tidak ditemukan: {$file}");
        }

        $zip = new ZipArchive();

        if ($zip->open($file) !== true) {
            throw new \RuntimeException('File Excel tidak dapat dibuka.');
        }

        $sharedStrings = $this->loadSharedStrings($zip);

        $xml = $zip->getStream('xl/worksheets/sheet1.xml');

        if (!$xml) {
            $zip->close();
            throw new \RuntimeException('sheet1.xml tidak ditemukan.');
        }

        $reader = new XMLReader();
        $reader->XML(stream_get_contents($xml));

        $headers = [
            'KODEITEM',
            'NAMAITEM',
            'JENIS',
            'MEREK',
            'SATUAN1',
            'SATUAN2',
            'SATUAN3',
            'SATUAN4',
            'BARCODE1',
            'BARCODE2',
            'BARCODE3',
            'BARCODE4',
            'KONVERSI1',
            'KONVERSI2',
            'KONVERSI3',
            'KONVERSI4',
            'HARGAPOKOK1',
            'HARGAPOKOK2',
            'HARGAPOKOK3',
            'HARGAPOKOK4',
            'HARGAJUAL1',
            'HARGAJUAL2',
            'HARGAJUAL3',
            'HARGAJUAL4',
            'POIN1',
            'POIN2',
            'POIN3',
            'POIN4',
            'KOMISISALES1',
            'KOMISISALES2',
            'KOMISISALES3',
            'KOMISISALES4',
            'STOK',
            'STOKMIN',
            'TIPE',
            'SERIAL',
            'RAK',
            'DEPT',
            'SUPPLIER',
            'KONSINYASI',
            'KETERANGAN',
            'SKU',
            'SKU2',
            'SKU3',
            'SKU4',
            'JENISPAJAK',
            'PAJAKINCLUDE',
            'KODEREFERENSI',
            'OPT',
        ];

        $rowNumber = 0;

        while ($reader->read()) {
            if ($reader->nodeType !== XMLReader::ELEMENT || $reader->name !== 'row') {
                continue;
            }

            $rowNumber++;

            if ($rowNumber === 1) {
                continue;
            }

            $row = $this->readRow($reader, $sharedStrings);

            if (empty($row)) {
                continue;
            }

            $data = [];

            foreach ($headers as $index => $header) {
                $data[$header] = $row[$index] ?? null;
            }

            $this->importProduct($data);

            if ($rowNumber % 500 === 0) {
                echo "Diproses: " . ($rowNumber - 1) . " produk\n";
            }
        }

        $reader->close();
        $zip->close();

        echo "Import selesai. Total baris diproses: " . ($rowNumber - 1) . "\n";
    }

    private function loadSharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');

        if ($xml === false) {
            return [];
        }

        $reader = new XMLReader();
        $reader->XML($xml);

        $strings = [];

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::ELEMENT && $reader->name === 'si') {
                $node = $reader->readOuterXml();

                $subReader = new XMLReader();
                $subReader->XML($node);

                $text = '';

                while ($subReader->read()) {
                    if ($subReader->nodeType === XMLReader::TEXT ||
                        $subReader->nodeType === XMLReader::CDATA) {
                        $text .= $subReader->value;
                    }
                }

                $subReader->close();

                $strings[] = $text;
            }
        }

        $reader->close();

        return $strings;
    }

    private function readRow(XMLReader $reader, array $sharedStrings): array
    {
        $rowXml = $reader->readOuterXml();

        $rowReader = new XMLReader();
        $rowReader->XML($rowXml);

        $values = [];

        while ($rowReader->read()) {
            if ($rowReader->nodeType !== XMLReader::ELEMENT || $rowReader->name !== 'c') {
                continue;
            }

            $cellRef = $rowReader->getAttribute('r');
            $type = $rowReader->getAttribute('t');

            $cellXml = $rowReader->readOuterXml();

            $cellReader = new XMLReader();
            $cellReader->XML($cellXml);

            $value = null;

            while ($cellReader->read()) {
                if ($cellReader->nodeType === XMLReader::ELEMENT && $cellReader->name === 'v') {
                    $cellReader->read();
                    $value = $cellReader->value;
                    break;
                }
            }

            $cellReader->close();

            if ($value !== null && $type === 's') {
                $value = $sharedStrings[(int) $value] ?? null;
            }

            $column = preg_replace('/[0-9]+/', '', $cellRef);

            $columnIndex = $this->columnToIndex($column);

            $values[$columnIndex] = $value;
        }

        $rowReader->close();

        if (empty($values)) {
            return [];
        }

        $max = max(array_keys($values));

        $result = array_fill(0, $max + 1, null);

        foreach ($values as $index => $value) {
            $result[$index] = $value;
        }

        return $result;
    }

    private function columnToIndex(string $column): int
    {
        $column = strtoupper($column);
        $length = strlen($column);
        $index = 0;

        for ($i = 0; $i < $length; $i++) {
            $index = ($index * 26) + (ord($column[$i]) - 64);
        }

        return $index - 1;
    }

    private function importProduct(array $data): void
    {
        $legacyCode = $this->clean($data['KODEITEM']);

        if (!$legacyCode) {
            return;
        }

        DB::transaction(function () use ($data, $legacyCode) {

            $categoryId = $this->findOrCreateCategory($data['JENIS']);
            $brandId = $this->findOrCreateBrand($data['MEREK']);

            $product = DB::table('products')
                ->where('legacy_code', $legacyCode)
                ->first();

            $productData = [
                'legacy_code' => $legacyCode,
                'name' => $this->clean($data['NAMAITEM']) ?: $legacyCode,
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'legacy_type' => $this->clean($data['TIPE']),
                'department' => $this->clean($data['DEPT']),
                'rack' => $this->clean($data['RAK']),
                'serial_flag' => $this->toBool($data['SERIAL']),
                'consignment_flag' => $this->toBool($data['KONSINYASI']),
                'tax_type' => $this->clean($data['JENISPAJAK']),
                'tax_included' => $this->toBool($data['PAJAKINCLUDE']),
                'minimum_stock' => $this->number($data['STOKMIN']),
                'current_stock' => $this->number($data['STOK']),
                'reference_code' => $this->clean($data['KODEREFERENSI']),
                'description' => $this->clean($data['KETERANGAN']),
                'status' => 'active',
                'updated_at' => now(),
            ];

            if ($product) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update($productData);

                $productId = $product->id;
            } else {
                $productData['initial_stock'] = $this->number($data['STOK']);
                $productData['created_at'] = now();

                $productId = DB::table('products')->insertGetId($productData);
            }

            for ($i = 1; $i <= 4; $i++) {
                $unitName = $this->clean($data["SATUAN{$i}"]);

                if (!$unitName) {
                    continue;
                }

                $conversion = $this->number($data["KONVERSI{$i}"]);

                if ($conversion <= 0) {
                    $conversion = $i === 1 ? 1 : 1;
                }

                $unitId = $this->findOrCreateUnit($unitName);

                $cost = $this->nullableNumber($data["HARGAPOKOK{$i}"]);
                $selling = $this->nullableNumber($data["HARGAJUAL{$i}"]);

 		 $productUnit = DB::table('product_units')
   			 ->where('product_id', $productId)
    			->where('unit_level', $i)
    			->first();

                $unitData = [
			'product_id' => $productId,
                        'unit_level' => $i,
                        'unit_name' => $unitName,
                        'abbreviation' => $unitName,
                        'conversion_to_base' => $conversion,
                        'cost_price' => $cost,
                        'default_selling_price' => $selling,
                    'updated_at' => now(),
                ];

                if ($productUnit) {
                    DB::table('product_units')
                        ->where('id', $productUnit->id)
                        ->update($unitData);

                    $productUnitId = $productUnit->id;
                } else {
                    $unitData['created_at'] = now();

                    $productUnitId = DB::table('product_units')
                        ->insertGetId($unitData);
                }

                $barcode = $this->clean($data["BARCODE{$i}"]);

                if ($barcode) {
   		 DB::table('product_barcodes')->updateOrInsert(
   		 [
        		'product_unit_id' => $productUnitId,
        		'barcode' => $barcode,
   		 ],
   		 [
       			 'is_primary' => $i === 1,
       			 'created_at' => now(),
   		 ]
		);
	}

                if ($selling !== null) {
DB::table('product_prices')->updateOrInsert(
    [
        'product_unit_id' => $productUnitId,
        'tier' => 1,
        'minimum_quantity' => 1,
    ],
    [
        'selling_price' => $selling,
        'updated_at' => now(),
        'created_at' => now(),
    ]
);
                }
            }
        });
    }

    private function findOrCreateCategory(?string $name): ?int
    {
        $name = $this->clean($name);

        if (!$name) {
            return null;
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $name), '-'));

        $existing = DB::table('categories')
            ->where('name', $name)
            ->first();

        if ($existing) {
            return $existing->id;
        }

        return DB::table('categories')->insertGetId([
            'name' => $name,
            'slug' => $slug ?: 'category-' . time(),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

private function findOrCreateBrand(?string $name): ?int
{
    $name = $this->clean($name);

    if (!$name || $name === '-') {
        return null;
    }

    $existing = DB::table('brands')
        ->where('name', $name)
        ->first();

    if ($existing) {
        return $existing->id;
    }

    return DB::table('brands')->insertGetId([
        'name' => $name,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

    private function findOrCreateUnit(string $name): int
    {
        $name = trim($name);

        $existing = DB::table('units')
            ->where('name', $name)
            ->orWhere('abbreviation', $name)
            ->first();

        if ($existing) {
            return $existing->id;
        }

        return DB::table('units')->insertGetId([
            'name' => $name,
            'abbreviation' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function clean($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function number($value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        return (float) str_replace(',', '.', (string) $value);
    }

    private function nullableNumber($value): ?float
    {
        $number = $this->number($value);

        return $number == 0 ? null : $number;
    }

    private function toBool($value): bool
    {
        return in_array(
            strtoupper(trim((string) $value)),
            ['1', 'Y', 'YES', 'TRUE', 'T'],
            true
        );
    }
}