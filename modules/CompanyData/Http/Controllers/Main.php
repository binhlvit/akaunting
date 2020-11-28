<?php

namespace Modules\CompanyData\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Abstracts\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\CompanyData\Jobs\UpdateCompany;
use Modules\CompanyData\Jobs\UpdateCompanyFromInternet;
use Modules\CompanyData\Models\CompanyData;

class Main extends Controller
{
    // todo
    public $alias = '';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $companies = CompanyData::collect(['created_at'=> 'desc']);
        return view('company-data::index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('company-data::create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Contact  $customer
     *
     * @return Response
     */
    public function edit(CompanyData $companyData)
    {
        return view('company-data::edit', compact('companyData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Contact $customer
     * @param  Request $request
     *
     * @return Response
     */
    public function update(CompanyData $companyData, Request $request)
    {
        $response = $this->ajaxDispatch(new UpdateCompany($companyData, $request->request->all()));

        if ($response['success']) {
            $response['redirect'] = route('company-data.index');

            $message = trans('messages.success.updated', ['type' => $companyData->company_name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('company-data.edit', $companyData->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
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

    public function updateFromInternet(Request $request){
        $companyData = CompanyData::where('id', $request->input('id'))->first();

        $response = $this->ajaxDispatch(new UpdateCompanyFromInternet($companyData, $request->request->all()));

        if ($response['success']) {
            $response['redirect'] = route('company-data.index');

            $message = trans('messages.success.updated', ['type' => $companyData->company_name]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('company-data.edit', $companyData->id);

            $message = $response['message'];

            flash($message)->error();
        }

        return response()->json($response);
    }
}
