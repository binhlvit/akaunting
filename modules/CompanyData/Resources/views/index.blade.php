@extends('layouts.admin')

@section('title', trans_choice('company-data::general.companies', 2))

@section('content')
    @if ($companies->count())
        <div class="card">

            <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'customers.index',
                    'role' => 'form',
                    'class' => 'mb-0'
                ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <akaunting-search
                        :placeholder="'{{ trans('general.search_placeholder') }}'"
                        :options="{{ json_encode([]) }}"
                    ></akaunting-search>
                </div>

                {{ Form::bulkActionRowGroup('general.customers', $bulk_actions, ['group' => 'sales', 'type' => 'customers']) }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                    <tr>
                        <th>{{ Form::bulkActionAllGroup() }}</th>
                        <th class="text-left">{{ trans('company-data::general.code') }}</th>
                        <th>@sortablelink('company_name', trans('company-data::general.company_name'))</th>
                        <th>{{ trans('company-data::general.representative') }}</th>
                        <th>@sortablelink('phone')</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($companies as $item)
                        <tr class=" align-items-center border-top-1">
                            <td>
                                {{ Form::bulkActionGroup($item->id, $item->code) }}
                            </td>
                            <td>
                                <a class="col-aka" href="{{ route('company-data.edit', $item->id) }}">{{ $item->code }}</a>
                            </td>
                            <td>
                                <a class="col-aka" href="{{ route('company-data.edit', $item->id) }}">{{ $item->company_name }}</a>
                            </td>
                            <td>
                                <el-tooltip content="{{ !empty($item->address) ? $item->address : trans('general.na') }}"
                                            effect="dark"
                                            placement="top">
                                    <span>{{ !empty($item->representative) ? $item->representative : trans('general.na') }}</span>
                                </el-tooltip>
                            </td>
                            <td>{{ $item->phone }}</td>
                            <td><button class="btn btn-primary btn-sm" v-on:click="updateCompanyInfo" data-company_id="{{ $item->id }}">{{ trans('company-data::general.update_from_internet')  }}</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer table-action">
                <div class="row">
                    @include('partials.admin.pagination', ['items' => $companies])
                </div>
            </div>
        </div>
    @else

    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/modules/CompanyData/Resources/assets/js/company-data-index.js?v=' . version('short')) }}"></script>
@endpush
