@extends('layouts.admin')

@section('title', trans_choice('company-data::general.zalo', 2))


@section('content')
    <div class="card">

        <div class="table-responsive">
            <table class="table align-items-center">
                <thead class="thead-light">
                <tr>
                    <th scope="col" class="sort" data-sort="budget">{{ trans('company-data::general.zalo_user_id') }}</th>
                    <th scope="col" class="sort" data-sort="name">{{ trans('company-data::general.avatar') }}</th>
                    <th scope="col" class="sort" data-sort="name">{{ trans('company-data::general.user_name') }}</th>
                    <th scope="col" class="sort" data-sort="budget">{{ trans('company-data::general.user_gender') }}</th>
                    <th scope="col" class="sort" data-sort="budget">{{ trans('company-data::general.birth_date') }}</th>
                    <th scope="col" ></th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach($users as $item)
                    <tr id="tr-{{ $item['user_id']  }}">
                        <td class="budget">{{ $item['user_id']  }}</td>
                        <td class="budget"><img src="{{ $item['avatar']  }}" width="50" alt="{{ $item['display_name']  }}"></td>
                        <td class="budget">{{ $item['display_name']  }}</td>
                        <td class="budget">{{ $item['user_gender']  }}</td>
                        <td class="budget">{{ $item['birth_date'] ?? '-'  }}</td>
                        <td class="budget"><i class="fa fa-comment" data-toggle="modal" data-target="#chatModal" @click="onSetUserId($event)" user_id="{{ $item['user_id']  }}" ></i></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open([
                       'route' => 'zalo.chat',
                       'id' => 'adv-zalo',
                       'files' => false,
                       'role' => 'form',
                       'class' => 'form-loading-button',
                       'novalidate' => true
                   ]) !!}
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">{{ trans('company-data::general.zalo_chat') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{ Form::textareaGroup('content', trans('company-data::general.content')) }}
                        <input type="hidden" id="user_id" name="user_id" />
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="onSendMessage"><i class="far fa-paper-plane"></i> {{ trans('company-data::general.send') }}</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
@endpush

