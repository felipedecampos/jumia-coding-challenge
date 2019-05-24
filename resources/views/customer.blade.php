@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
                                <h2><strong>Phone numbers</strong></h2>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ Request::fullUrlWithQuery(['sort' => 'country']) }}">
                                            Country
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ Request::fullUrlWithQuery(['sort' => 'state']) }}">
                                            State
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ Request::fullUrlWithQuery(['sort' => 'code']) }}">
                                            Country code
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ Request::fullUrlWithQuery(['sort' => 'phone_number']) }}">
                                            Phone num.
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $customerKey => $customer)
                                    <tr>
                                        <td>
                                            {{ $customer->country }}
                                        </td>
                                        <td>
                                            @switch($customer->state)
                                                @case('OK')
                                                <span class="badge badge-success">OK</span>
                                                @break

                                                @case('NOK')
                                                @default
                                                <span class="badge badge-danger">NOK</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            +{{ $customer->code }}
                                        </td>
                                        <td>
                                            {{ $customer->phone_number }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            {{ 'Nothing to show!' }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ $customers->appends(Request::query())->render() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
