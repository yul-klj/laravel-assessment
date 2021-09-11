<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Export
 *
 * To keep the logs for frontend triggers generate in backend
 *
 * @package App\Models
 * @author Yul <yul_klj@hotmail.com>
 */
class Export extends Model
{
    use HasFactory;

    const CSV_EXPORT_TYPE = 'CSV';
    const XML_EXPORT_TYPE = 'XML';
    const ALL_EXPORT_TYPE = [
        self::CSV_EXPORT_TYPE,
        self::XML_EXPORT_TYPE
    ];

    protected $table = 'exports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'fields', 'location'];
}
