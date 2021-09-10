<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Book
 *
 * @package App\Models
 * @author Yul <yul_klj@hotmail.com>
 */
class Book extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'author'];
}
