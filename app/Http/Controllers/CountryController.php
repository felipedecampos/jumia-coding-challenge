<?php

namespace App\Http\Controllers;

use App\Repositories\CountryRepository;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $countries = $this->countryRepository->listAll();

        return response()->json($countries);
    }
}
