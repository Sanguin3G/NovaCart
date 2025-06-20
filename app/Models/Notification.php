<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $fillable = [
        'sender_id', 'content', 'data', 'json_push', 'screen', 'reference_id', 'type', 'status'
    ];

    public function getById($id)
    {
        if (!is_numeric($id)) {
            return false;
        }

        return $this->where($this->primaryKey, $id)
            ->with('userPush.user.deviceToken')
            ->first();
    }

    public function updateNotification($id, $data)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }
}
