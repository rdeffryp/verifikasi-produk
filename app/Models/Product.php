<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'products';

    /**
     * Primary key
     */
    protected $primaryKey = 'id';

    /**
     * Field yang boleh di-mass assignment
     */
    protected $fillable = [
        'product_id',
        'nama_produk',
        'tanggal_produksi',
        'tanggal_kadaluarsa',
        'batch_number',
        'hash_data',
        'signature',
        'is_verified',
        'first_scan_at',
        'last_scan_at',
        'scan_count',
        'qr_code_path',
        'created_by',
    ];

    /**
     * Field yang harus di-cast ke tipe data tertentu
     */
    protected $casts = [
        'tanggal_produksi' => 'date',
        'tanggal_kadaluarsa' => 'date',
        'is_verified' => 'boolean',
        'first_scan_at' => 'datetime',
        'last_scan_at' => 'datetime',
        'scan_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Field yang di-hidden saat serialization (JSON)
     */
    protected $hidden = [
        // Tidak perlu hide apapun untuk sekarang
    ];

    /**
     * Scope untuk produk yang sudah terverifikasi
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope untuk produk yang belum terverifikasi
     */
    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }

    /**
     * Scope untuk produk yang sudah kadaluarsa
     */
    public function scopeExpired($query)
    {
        return $query->where('tanggal_kadaluarsa', '<', now());
    }

    /**
     * Scope untuk produk yang masih valid (belum kadaluarsa)
     */
    public function scopeValid($query)
    {
        return $query->where('tanggal_kadaluarsa', '>=', now());
    }

    /**
     * Accessor untuk status produk
     */
    public function getStatusAttribute()
    {
        if ($this->tanggal_kadaluarsa < now()) {
            return 'KADALUARSA';
        }
        
        if (!$this->is_verified) {
            return 'BELUM_DISCAN';
        }
        
       if ($this->scan_count <= 5) {
            return 'ASLI';
        }
        
        if ($this->scan_count > 5) {
            return 'DUPLIKASI';
        }
        
        return 'UNKNOWN';
    }

    /**
     * Cek apakah produk sudah kadaluarsa
     */
    public function isExpired()
    {
        return $this->tanggal_kadaluarsa < now();
    }

    /**
     * Cek apakah produk valid (belum kadaluarsa)
     */
    public function isValid()
    {
        return $this->tanggal_kadaluarsa >= now();
    }

    /**
     * Generate Product ID
     * Format: RZ20250125-xxxx
     */
    public static function generateProductId()
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
        return "RZ{$date}-{$random}";
    }
}