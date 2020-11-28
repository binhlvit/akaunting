<?php

namespace Modules\CompanyData\Jobs;

use App\Abstracts\Job;

class UpdateCompany extends Job
{
    protected $companyData;

    protected $requestInput;

    /**
     * Create a new job instance.
     *
     * @param  $company
     * @param  $request
     */
    public function __construct($company, $requestInput)
    {
        $this->companyData = $company;
        $this->requestInput = $requestInput;
    }

    public function handle()
    {
        \DB::transaction(function () {
            $this->companyData->update($this->requestInput);
        });

        return $this->companyData;
    }
}
