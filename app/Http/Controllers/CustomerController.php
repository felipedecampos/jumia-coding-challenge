<?php

namespace App\Http\Controllers;

use App\Repositories\CustomerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters   = $this->handleFilters($request);
        $customers = $this->customerRepository->getAll($filters);

        return response()->json($customers);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function handleFilters(Request $request): array
    {
        $filters = [];

        if ($request->has('country') && is_numeric($request->get('country'))) {
            $filters['country'] = ['country.id', '=', (int) $request->get('country')];
        }

        if ($request->has('state') && in_array($request->get('state'), ['OK', 'NOK'])) {
            $filters['state'] = $request->get('state');
        }

        return $filters;
    }
}
