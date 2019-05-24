<?php

use App\Repositories\CountryRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;
use Illuminate\Http\Response;

class CountriesTableSeeder extends Seeder
{
    /**
     * @var DatabaseManager
     */
    protected $db;

    /**
     * @var CountryRepository
     */
    public $country;

    /**
     * CountriesTableSeeder constructor.
     * @param DatabaseManager $db
     * @param CountryRepository $country
     */
    public function __construct(DatabaseManager $db, CountryRepository $country)
    {
        $this->db      = $db;
        $this->country = $country;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        try {
            $this->db->beginTransaction();

            $result = $this->country->createMany([
                [
                    'name' => 'Cameroon',
                    'code' => 237
                ],
                [
                    'name' => 'Ethiopia',
                    'code' => 251
                ],
                [
                    'name' => 'Morocco',
                    'code' => 212
                ],
                [
                    'name' => 'Mozambique',
                    'code' => 258
                ],
                [
                    'name' => 'Uganda',
                    'code' => 256
                ]
            ]);

            if (true !== $result) {
                throw new Exception(
                    'Could not possible to create the countries, please, try again later.',
                    Response::HTTP_EXPECTATION_FAILED
                );
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}