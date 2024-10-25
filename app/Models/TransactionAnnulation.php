<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionAnnulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'client_id',
        'agent_id',
        'requested_at',
        'cancelled_at',
    ];
}
