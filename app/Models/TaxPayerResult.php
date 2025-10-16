<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxPayerResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'ident_code',
        'status',
        'registered_subject',
        'full_name',
        'start_date',
        'vat_payer',
        'mortgage',
        'sequestration',
        'additional_status',
        'non_resident',
        'response_status',
        'error_message',
        'raw_response',
        'name',
        'user',
        'gift_name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function isSuccessful()
    {
        return $this->response_status === 'success';
    }

    public function hasError()
    {
        return $this->response_status === 'error';
    }

    public function getFormattedStartDateAttribute()
    {
        if ($this->start_date) {
            try {
                return date('Y-m-d H:i:s', strtotime($this->start_date));
            } catch (\Exception $e) {
                return $this->start_date;
            }
        }
        return null;
    }
}
