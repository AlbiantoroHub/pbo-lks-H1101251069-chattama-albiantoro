<?php
namespace App\Models;

class Category
{
    private int $id;
    private string $nama;
    private string $deskripsi;

    public function __construct(string $nama, string $deskripsi, int $id = 0)
    {
        $this->nama = $nama;
        $this->deskripsi = $deskripsi;
        $this->id = $id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi
        ];
    }
}