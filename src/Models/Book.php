<?php
namespace App\Models;

class Book
{
    private ?int $id;
    private string $judul;
    private string $penulis;
    private int $tahun;
    private int $stok;
    private ?string $created_at;
    private ?string $updated_at;

    public function __construct(
        string $judul,
        string $penulis,
        int $tahun,
        int $stok = 0,
        ?int $id = null,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->judul = $judul;
        $this->penulis = $penulis;
        $this->tahun = $tahun;
        $this->stok = $stok;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): ?int { return $this->id; }
    public function getJudul(): string { return $this->judul; }
    public function getPenulis(): string { return $this->penulis; }
    public function getTahun(): int { return $this->tahun; }
    public function getStok(): int { return $this->stok; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getUpdatedAt(): ?string { return $this->updated_at; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'tahun' => $this->tahun,
            'stok' => $this->stok,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}