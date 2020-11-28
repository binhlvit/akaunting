@extends('layouts.admin')

@section('title', trans('hosing-domain::general.name'))

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">{{ trans('hosing-domain::general.domain.check') }}</h3>
                </div>

                {!! Form::open([
                    'id' => 'check-domain',
                    'route' => 'hosing-domain.check-domain',
                    'files' => true,
                    'role' => 'form',
                    'class' => 'form-loading-button',
                    'novalidate' => true,
                ]) !!}

                <div class="card-body">
                    <div class="row">
                        {{ Form::textGroup('domain', trans('hosing-domain::general.domain.enter_domain'), '', ['required' => 'required'], null, 'col-md-12') }}
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row float-right">
                        {{ Form::saveButtons('settings.index') }}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
@stop
