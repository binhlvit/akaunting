@extends('layouts.admin')

@section('title', trans_choice('company-data::general.companies', 2))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">{{ trans('company-data::general.update') }}</h3>
                </div>

                {!! Form::model($companyData, [
                    'id' => 'company_data',
                    'route' => ['company-data.update', $companyData->id],
                    'role' => 'form',
                    'id' => 'companyData',
                    '@submit.prevent' => 'onSubmit',
                    '@keydown' => 'form.errors.clear($event.target.name)',
                    'class' => 'form-loading-button',
                    'novalidate' => 'true'
                ]) !!}

                    <div class="card-body">
                        <div class="row">
                            {{ Form::textGroup('code', trans('company-data::general.code'), '', ['required' => 'required'], null, 'col-md-12') }}
                            {{ Form::textGroup('company_name', trans('company-data::general.company_name'), '', ['required' => 'required'], null, 'col-md-12') }}
                            {{ Form::textGroup('company_name_en', trans('company-data::general.company_name_en'), '', [], null, 'col-md-12') }}
                            {{ Form::textGroup('address', trans('company-data::general.address'), '', [], null, 'col-md-12') }}
                            {{ Form::textGroup('phone', trans('company-data::general.phone'), '', [], null, 'col-md-6') }}
                            {{ Form::textGroup('representative', trans('company-data::general.representative'), '', [], null, 'col-md-6') }}
                            {{ Form::textGroup('date_of_incorporation', trans('company-data::general.date_of_incorporation'), '', [], null, 'col-md-6') }}
                            {{ Form::textGroup('type_text', trans('company-data::general.type_text'), '', [], null, 'col-md-6') }}
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row float-right">
                            {{ Form::saveButtons('company-data.index') }}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/modules/CompanyData/Resources/assets/js/company-data.js?v=' . version('short')) }}"></script>
@endpush
