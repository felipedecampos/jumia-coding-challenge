<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;

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
     * @param array $filters
     * @param string $orderColumn
     * @param string $orderDirection
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function getAll(
        array $filters = [],
        string $orderColumn = 'country.name',
        string $orderDirection = 'ASC',
        int $paginate = 10
    )
    {
        $query = $this->customer->query()
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
            ->orderBy($orderColumn, $orderDirection);

        if (isset($filters['country']) && is_array($filters['country']) && count($filters['country']) === 3) {
            $query->where($filters['country'][0], $filters['country'][1], $filters['country'][2]);
        }

        $result = isset($filters['state']) && in_array($filters['state'], ['OK', 'NOK'])
            ? $query->get()->where('state', '=', $filters['state'])
            : $query->get();

        return $this->customPaginate($result, $paginate);
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

    /**
     * @param Collection $items
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    private function customPaginate(Collection $items, int $perPage): LengthAwarePaginator
    {
        $pageStart           = request('page', 1);
        $offSet              = ($pageStart * $perPage) - $perPage;
        $itemsForCurrentPage = $items->slice($offSet, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsForCurrentPage, $items->count(), $perPage,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }
}