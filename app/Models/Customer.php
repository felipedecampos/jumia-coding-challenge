<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * @package App\Models
 */
class Customer extends Model
{
    /**
     * @var string
     */
    protected $table      = 'customer';
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
        'phone'
    ];
    /**
     * @var array
     */
    protected $appends = [
        'state',
        'phone_number'
    ];

    /**
     * Get mutator state
     * @return string
     */
    public function getStateAttribute(): string
    {
        switch (true) {
            case preg_match("/\(237\)\ ?[2368]\d{7,8}$/", $this->phone): // Cameroon
            case preg_match("/\(251\)\ ?[1-59]\d{8}$/", $this->phone): // Ethiopia
            case preg_match("/\(212\)\ ?[5-9]\d{8}$/", $this->phone): // Morocco
            case preg_match("/\(258\)\ ?[28]\d{7,8}$/", $this->phone): // Mozambique
            case preg_match("/\(256\)\ ?\d{9}$/", $this->phone): // Uganda
                $state = 'OK';
                break;
            default:
                $state = 'NOK';
        }

        return $state;
    }

    /**
     * Set mutator with the phone validation
     * @param bool $state
     */
    public function setStateAttribute(bool $state)
    {
        $this->attributes['state'] = $state;
    }

    /**
     * Get the formatted phone
     * @return string
     */
    public function getPhoneNumberAttribute(): string
    {
        return substr($this->phone, 6);
    }
}
