<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
  
class Event extends Model
{
    use HasFactory;
  
    protected $fillable = [
        'title', 'start', 'end','admin','penyewa','description','clasification','color','form_acc','bukti_bayar','biaya'
    ];
}