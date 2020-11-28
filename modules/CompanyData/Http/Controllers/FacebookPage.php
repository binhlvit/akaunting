<?php

namespace Modules\CompanyData\Http\Controllers;

use App\Abstracts\Http\Controller;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Zalo\Zalo;
use Zalo\ZaloEndPoint;

class FacebookPage extends Controller
{
    public function listBaiViet(){
        $fb = new Facebook([
            'app_id' => '1702733799950116',
            'app_secret' => '9771c19d31042650580cd85647d1cd94',
            'default_graph_version' => 'v9.0',
            'default_access_token' => 'EAAYMoJtLByQBANELRxhJuEZAfNxPIuPLHrMyNzuSW7ZAcy6lnjieMowH36R2Kpm9S6TPB1VWPbOZAVxW2oi3PF7OVFrPEIHeLcTYXE9ImuxljRSjydgSRgEaXdWUZCrUxol7kyErUmhZBTY0OkIa7aDN3brVrd6DQEaSzGLYwi9ZBdcPS88d9rtyBviUF742gZD'
        ]);
    }
}
