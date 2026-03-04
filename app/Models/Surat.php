<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'sender_user_id',
        'sender_division',
        'recipient_division',
        'nomor_surat',
        'jenis',
        'judul',
        'isi',
        'lampiran_path',
        'lampiran_name',
        'status',
        'sent_at',
        'read_at',
        'replied_at',
        'completed_at',
        'archived_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
        'completed_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function parent()
    {
        return $this->belongsTo(Surat::class, 'parent_id');
    }
}
