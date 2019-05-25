<?php

namespace App\Repositories;

use App\Models\Country;
use Illuminate\Support\Collection;

/**
 * Class CountryRepository
 * @codeCoverageIgnore
 */
class CountryRepository
{
    /**
     * @var Country
     */
    public $country;

    /**
     * CountryRepository constructor.
     * @param Country $country
     */
    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    /**
     * Create new records in the database
     * @param array $data
     * @return bool
     */
    public function createMany(array $data): bool
    {
        return $this->country->insert($data);
    }

    /**
     * List all countries
     * @return Collection
     */
    public function listAll(): Collection
    {
        return $this->country->query()
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id');
    }
}