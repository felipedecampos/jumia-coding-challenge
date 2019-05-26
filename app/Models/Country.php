<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Country
 * @package App\Models
 */
class Country extends Model
{
    /**
     * @var string
     */
    protected $table      = 'country';
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var array
     */
    protected $hidden     = ['id'];
    /**
     * @var array
     */
    protected $fillable   = [
        'name',
        'code'
    ];
}
