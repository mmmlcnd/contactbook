<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadHistory extends Model
{
    use HasFactory;

    protected $table = 'read_histories';

    public $timestamps = false;

    protected $fillable = [
        'entry_id',
        'teacher_id',
        'stamped_at',
    ];

    /**
     * Entryモデルとのリレーションシップ（この確認履歴がどの連絡帳に属するか）
     */
    public function entry()
    {
        return $this->belongsTo(Entry::class, 'entry_id');
    }

    /**
     * Teacherモデルとのリレーションシップ
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
