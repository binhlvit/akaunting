<?php

namespace Modules\CompanyData\Http\Controllers;

use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Zalo\Zalo;
use Zalo\ZaloEndPoint;

class ZaloChat extends Controller
{
    protected $zalo;

    public function __construct()
    {
        $config = [
            'app_id' => '1928921339508421101',
            'app_secret' => 'QK8MkCpL4Z6EDKFJRTcF',
            'callback_url' => '',
            'default_access_token' => 'Yb-7Uaxjarg9L8rnIkwOLuvLX7L9yVCs-3YSRaoBnJ7iJx8MOutCKEPFjouzejrxZ72A0Kks_MJVMuCnU-3RTA0lbozd-lP7foJiBoRMa4w4ESSa7_w0KOD0vHeW_QvbeJdt60_0xM-t8F4KCPoEOlv2-nXfiQ5LxKFPDbQ6dNp8UiaSOSBIF_0zc4epsFmGYJI6SH7oXZQY4EvBBCdqEx5sksOCijmbgLUbPoktvIsHLw0999dzLBfdud4lcFehdKUQS3k9yG6qSkzGBOgI1VQABH9F-vjH'
        ];
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $accessToken = 'VILHRwx6F0T-BbjagiKt2dalKc2fsb9nE6j46kh7Md9gS5vg_kn17Me7LdVVxsT0KmTTKugMNNWwU3rHWSqXKZCx9bUhhpOWBqnR6xZ5K2uEJ7GugDmL0mjCS1Iir6m_E65j7O7CTYiD9sWybgP60X8wPHF2i7zzKp9WHkZ40cXaMX5ZoUmEKMHoC6ZIoWjW0bWc3xBQInu_MNeWkiSM4IvD3p6t_sW2DszN7ux_MdmeHNvNcEPWLpCCGMA4bbvGK1XFRCcY8b1-6q5-zgjAIqTeuTjKMg_GD0m';
        $users = [];
        $client = new \GuzzleHttp\Client();
        try {
            $re = $client->request('GET', 'https://openapi.zalo.me/v2.0/oa/getfollowers', [
                'query' => [
                    'access_token' => $accessToken,
                    'data' => json_encode(array(
                        'offset' => 0,
                        'count' => 10
                    ))
                ]
            ]);

            $data = $re->getBody();
            $data = \GuzzleHttp\json_decode($data, true);

            if(isset($data['data']['followers']) && $data['data']['followers']){
                foreach($data['data']['followers'] as $userId){
                    $zaloUser = $client->request('GET', 'https://openapi.zalo.me/v2.0/oa/getprofile', [
                        'query' => [
                            'access_token' => $accessToken,
                            'data' => json_encode(array(
                                'user_id' => $userId['user_id']
                            ))
                        ]
                    ]);
                    $zaloUser = $zaloUser->getBody();
                    $zaloUser = \GuzzleHttp\json_decode($zaloUser, true);
                    if(isset($zaloUser['data']) && $zaloUser['data']){
                        $users[] = $zaloUser['data'];
                    }
                }
            }
        } catch (RequestException $e) {
        }


        return view('company-data::chat.zalo.list',  compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $accessToken = 'Yb-7Uaxjarg9L8rnIkwOLuvLX7L9yVCs-3YSRaoBnJ7iJx8MOutCKEPFjouzejrxZ72A0Kks_MJVMuCnU-3RTA0lbozd-lP7foJiBoRMa4w4ESSa7_w0KOD0vHeW_QvbeJdt60_0xM-t8F4KCPoEOlv2-nXfiQ5LxKFPDbQ6dNp8UiaSOSBIF_0zc4epsFmGYJI6SH7oXZQY4EvBBCdqEx5sksOCijmbgLUbPoktvIsHLw0999dzLBfdud4lcFehdKUQS3k9yG6qSkzGBOgI1VQABH9F-vjH';
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', 'https://openapi.zalo.me/v2.0/oa/message', [
                'query' => [
                    'access_token' => $accessToken
                ],
                'json' => [
                    'recipient' => ['user_id' => $request->input('user_id')],
                    'message' => ['text' => $request->input('content')],
                ]
            ]);

            $data = $response->getBody();
            $data = \GuzzleHttp\json_decode($data, true);

            if($data['error'] != 0){
                throw new \Exception($data['message'], 100);
            }

            $data['code'] = 200;
            $data['message'] = $data['data']['message_id'];

        } catch (\Exception $e) {
            $data['code'] = $e->getCode();
            $data['message'] = $e->getMessage();
        }

        dump($data);die;

        return response()->json($data);
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('btsadv::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('btsadv::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('btsadv::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
