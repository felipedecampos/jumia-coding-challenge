<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;

/**
 * Class CustomerRepository
 * @codeCoverageIgnore
 */
class CustomerRepository
{
    /**
     * @var DatabaseManager
     */
    protected $db;

    /**
     * @var Customer
     */
    public $customer;

    /**
     * CustomerRepository constructor.
     * @param DatabaseManager $db
     * @param Customer $customer
     */
    public function __construct(DatabaseManager $db, Customer $customer)
    {
        $this->db       = $db;
        $this->customer = $customer;
    }

    /**
     * Get all instances of model
     * @param string $orderColumn
     * @param string $orderDirection
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function getAll(
        string $orderColumn = 'country.name',
        string $orderDirection = 'ASC',
        int $paginate = 10
    )
    {
        return $this->customer->query()
            ->select([
                'customer.id',
                'customer.name',
                'customer.phone',
                $this->db->raw('country.name AS country'),
                'country.code'
            ])
            ->leftJoin('country', function($join) {
                $join->on('country.code', '=', $this->db->raw('SUBSTR(`customer`.`phone`, 2, 3)'));
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate($paginate);
    }

    /**
     * Get the record with the given id
     * @param $id
     * @return Customer
     */
    public function find($id): Customer
    {
        return $this->customer->findOrFail($id);
    }
}