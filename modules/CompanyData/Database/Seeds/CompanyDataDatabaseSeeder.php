<?php

namespace Modules\CompanyData\Database\Seeds;

use App\Abstracts\Model;
use Illuminate\Database\Seeder;

class CompanyDataDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        Model::reguard();
    }
}
