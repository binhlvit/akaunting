<?php

namespace Modules\CompanyData\Jobs;

use App\Abstracts\Job;
use Goutte\Client;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class UpdateCompanyFromInternet extends Job
{
    protected $companyData;
    protected $companyDataInput;

    protected $requestInput;

    /**
     * Create a new job instance.
     *
     * @param  $company
     * @param  $request
     */
    public function __construct($company, $request)
    {
        $this->companyData = $company;
        $this->requestInput = $request;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->companyDataFinal = $this->companyData;
        $url = 'https://masothue.vn';
        $client = new Client();

        $urlGetCompanyInfo = sprintf('%s/%s-%s', $url, $this->companyData->code, Str::slug($this->companyData->company_name, '-'));

        $crawler = $client->request('GET', $urlGetCompanyInfo, ['PageSpeed' => 'noscript']);

        $arrayUpdates = [
            'address'               => 'table.table-taxinfo td[itemprop="address"]',
            'company_name_en'       => 'table.table-taxinfo td[itemprop="alternateName"]',
            'representative'        => 'table.table-taxinfo td > span[itemprop="name"]',
            'phone'                 => 'table.table-taxinfo td[itemprop="telephone"]',
            'type_text'             => 'table.table-taxinfo td[itemprop="telephone"]',
        ];

        foreach($arrayUpdates as $keyDb => $keyCrawler){
            $infoValue = $crawler->filter($keyCrawler)->first()->text('');
            if(!empty($infoValue)){
                if(!empty($infoValue) && $infoValue != $this->companyData->{$keyDb}){
                    $this->companyDataInput[$keyDb] = $infoValue;
                }
            }
        }

        // Lấy các thuộc tính mở rộng không thể lấy bằng cách thông thường
        $crawler->filter('table.table-taxinfo td')->each(function (Crawler $nodeTr, $i) {
            $case = $nodeTr->text('none');
            switch ($case){
                case 'Loại hình DN':
                    $keyDb = 'type_text';
                    $infoValue = $nodeTr->parents()->filter('td')->eq(1)->text();
                    if(!empty($infoValue) && $infoValue != $this->companyData->{$keyDb}){
                        $this->companyDataInput[$keyDb] = $infoValue;
                    }
                    break;
                case 'Ngày hoạt động':
                    $keyDb = 'date_of_incorporation';
                    $infoValue = $nodeTr->parents()->filter('td')->eq(1)->text();
                    if(!empty($infoValue) && $infoValue != $this->companyData->{$keyDb}){
                        $this->companyDataInput[$keyDb] = $infoValue;
                    }
                    break;
                case 'Tên viết tắt':
                    $keyDb = 'company_name_acronym';
                    $infoValue = $nodeTr->parents()->filter('td')->eq(1)->text();
                    if(!empty($infoValue) && $infoValue != $this->companyData->{$keyDb}){
                        $this->companyDataInput[$keyDb] = $infoValue;
                    }
                    break;
            }
        });

        $this->cleanData();

        $note = $crawler->filter('table.table')->outerHtml();
        if(!empty($note)){
            $this->companyData->note = $note;
        }


        \DB::transaction(function () {
            $this->companyData->save();
        });

        return $this->companyData;
    }

    protected function cleanData(){
        if(isset($this->companyDataInput['phone']) && !empty(preg_replace('/\D/', '', $this->companyDataInput['phone']))){
            $this->companyData->phone = preg_replace('/\D/', '', $this->companyDataInput['phone']);
        }
    }



}
