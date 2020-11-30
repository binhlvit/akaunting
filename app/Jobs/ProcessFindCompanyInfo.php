<?php

namespace App\Jobs;

use Goutte\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Modules\CompanyData\Models\CompanyData;
use Symfony\Component\DomCrawler\Crawler;

class ProcessFindCompanyInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $companyData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CompanyData $companyData)
    {
        $this->companyData = $companyData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'https://masothue.vn';
        $client = new Client();

        $urlGetCompanyInfo = sprintf('%s/%s-%s', $url, $this->companyData->code, Str::slug($this->companyData->company_name, '-'));

        $crawler = $client->request('GET', $urlGetCompanyInfo, ['PageSpeed' => 'noscript']);

        $arrayUpdates = [
            'address'               => 'table.table-taxinfo td[itemprop="address"]',
            'company_name_en'       => 'table.table-taxinfo td[itemprop="alternateName"]',
            'representative'        => 'table.table-taxinfo td > span[itemprop="name"]',
            'phone'                 => 'table.table-taxinfo td[itemprop="telephone"]'
        ];

        foreach($arrayUpdates as $keyDb => $keyCrawler){
            $infoValue = $crawler->filter($keyCrawler)->first()->text('');
            if(!empty($infoValue)){
                if(!empty($infoValue) && $infoValue != $this->companyData->{$keyDb}){
                    $this->companyData->{$keyDb} = $infoValue;
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
                        $this->companyData->{$keyDb} = $infoValue;
                    }
                    break;
                case 'Ngày hoạt động':
                    $keyDb = 'date_of_incorporation';
                    $infoValue = $nodeTr->parents()->filter('td')->eq(1)->text();
                    if(!empty($infoValue) && $infoValue != $this->companyData->{$keyDb}){
                        $this->companyData->{$keyDb} = $infoValue;
                    }
                    break;
                case 'Tên viết tắt':
                    $keyDb = 'company_name_acronym';
                    $infoValue = $nodeTr->parents()->filter('td')->eq(1)->text();
                    if(!empty($infoValue) && $infoValue != $this->companyData->{$keyDb}){
                        $this->companyData->{$keyDb} = $infoValue;
                    }
                    break;
            }
        });

        $this->cleanData();

        $note = $crawler->filter('table.table')->outerHtml();
        if(!empty($note)){
            $this->companyData->note = $note;
        }

        // Update to DB
        $this->companyData->save();
    }

    protected function cleanData(){
        $this->companyData->phone = preg_replace('/\D/', '', $this->companyData->phone);
    }
}
