<?php

namespace App\Console\Commands;

use App\Jobs\ProcessFindCompanyInfo;
use Goutte\Client;
use Illuminate\Console\Command;
use Modules\CompanyData\Models\CompanyData;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerCommand extends Command
{
    protected $signature = 'crawler:c';
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crawler:c';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = 'https://bocaodientu.dkkd.gov.vn/egazette/Forms/Egazette/ANNOUNCEMENTSListingInsUpd.aspx?h=1a2';

        $client = new Client();

        $crawler = $client->request('GET', $url);

        $count  = 1;
        while ($count < 10){
            $form = $crawler->filter('#aspnetForm')->form();
            $form['ctl00$C$ANNOUNCEMENT_TYPE_IDFilterFld'] = 'NEW';
            $form['ctl00$C$HO_PROVINCE_IDFld'] = '87';
            $form['ctl00$C$WebTextBox1'] = '';
            $form['ctl00$C$ENT_NAMEFilterFld'] = '';
            $form['ctl00$C$ENT_GDT_CODEFld'] = '';
            $form['ctl00$C$ENT_CODEFilterFld'] = '';
            $form['ctl00$C$PUBLISH_DATEFilterFld'] = '';
            $form['ctl00$C$PUBLISH_DATEFilterFldTo'] = '';
            $form['ctl00$C$PUBLISH_DATEFilterFldFrom'] = '';
            $form['ctl00$FldSearchID'] = '';
            $form['ctl00$FldSearch'] = '';
            $values = array_merge_recursive(
                $form->getPhpValues(),
                [
                    'ctl00$C$BtnFilter' => 'Tìm kiếm',
                ]
            );
            if($count != 1){
                $values = array_merge_recursive(
                    $values,
                    [
                        '__EVENTTARGET' => 'ctl00$C$CtlList',
                        '__EVENTARGUMENT' => 'Page$'.$count,
                        'ctl00$C$BtnFilter' => 'Tìm kiếm',
                    ]
                );
            }

            // Submit all the values.
            $crawler = $client->request($form->getMethod(), $form->getUri(), $values);

            $crawler->filter('table#ctl00_C_CtlList tr')->each(
                function (Crawler $nodeTr, $i) {
                    if($i && $nodeTr->attr('class') != 'Pager'){
                        $nodeTd2 = $nodeTr->filter('td')->eq(3);
                        $code = preg_replace('/\D/', '', $nodeTd2->filter('div.enterprise_code')->first()->text(''));

                        if(!empty($code) && CompanyData::where('code', $code)->first() == false){
                            $CompanyData = new CompanyData();
                            $CompanyData->company_name = $nodeTd2->filter('p.enterprise_name')->first()->text('');
                            $CompanyData->code = $code;
                            $CompanyData->save();

                            // Update company info
                            ProcessFindCompanyInfo::dispatch($CompanyData);

                        }
                    }
                }
            );

            $count ++;
            sleep(5);
        }
    }
    public function handle1111()
    {
        $url = 'https://bocaodientu.dkkd.gov.vn/egazette/Forms/Egazette/DefaultAnnouncements.aspx';

        $client = new Client();

        $crawler = $client->request('GET', $url);

        $companies[] = $crawler->filter('table#ctl00_C_CtlList tr')->each(
            function (Crawler $nodeTr, $i) {
                if($i && $nodeTr->attr('class') != 'Pager'){
                    $nodeTd2 = $nodeTr->filter('td')->eq(1);
                    return [
                        'time' => $nodeTr->filter('td')->eq(0)->text(),
                        'name' => $nodeTd2->filter('p.enterprise_name')->first()->text(''),
                        'code' => $nodeTd2->filter('div.enterprise_code')->first()->text(''),
                        'address' => $nodeTr->filter('td')->eq(2)->text(),
                    ];
                }
            }
        );




        $count  = 2;
        while ($count < 5){
            $form = $crawler->filter('#aspnetForm')->form();
            $values = array_merge_recursive(
                $form->getPhpValues(),
                [
                    '__EVENTTARGET' => 'ctl00$C$CtlList',
                    '__EVENTARGUMENT' => 'Page$'.$count,
                ]
            );

            var_dump($values);die;

            // Submit all the values.
            $crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

            $companies[] = $crawler->filter('table#ctl00_C_CtlList tr')->each(
                function (Crawler $nodeTr, $i) {
                    if($i && $nodeTr->attr('class') != 'Pager'){
                        $nodeTd2 = $nodeTr->filter('td')->eq(1);
                        return [
                            'time' => $nodeTr->filter('td')->eq(0)->text(),
                            'name' => $nodeTd2->filter('p.enterprise_name')->first()->text(''),
                            'code' => $nodeTd2->filter('div.enterprise_code')->first()->text(''),
                            'address' => $nodeTr->filter('td')->eq(2)->text(),
                        ];
                    }
                }
            );
            $count ++;
        }

        dump($companies);die;
    }
    protected function appendPrototypeDom(\DOMElement $node, $currentIndex = 0, $count = 1)
    {
        $prototypeHTML = $node->getAttribute('data-prototype');
        $accumulatedHtml = '';
        for ($i = 0; $i < $count; $i++) {
            $accumulatedHtml .= str_replace('__name__', $currentIndex + $i, $prototypeHTML);
        }
        $prototypeFragment = new \DOMDocument();
        $prototypeFragment->loadHTML($accumulatedHtml);
        foreach ($prototypeFragment->getElementsByTagName('body')->item(0)->childNodes as $prototypeNode) {
            $node->appendChild($node->ownerDocument->importNode($prototypeNode, true));
        }
    }

}
