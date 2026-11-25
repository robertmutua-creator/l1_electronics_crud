<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row): Product
    {
        return new Product([
            'name' => Str::title($row['name']),
            'brand' => Str::title($row['brand']),
            'price' => $row['price'],
            'quantity' => $row['quantity'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.brand' => 'required|string|max:255',
            '*.price' => 'required|numeric|min:0',
            '*.quantity' => 'required|integer|min:0',
        ];
    }
}
