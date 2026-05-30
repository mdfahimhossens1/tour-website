@extends('layouts.admin')
@section('title','Manage Settings')

@section('page')

<div class="row">
    <div class="col-md-12">

        <div class="card">

            <div class="card-header">
                <h5>Manage Settings</h5>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- GENERAL SETTINGS --}}
                    <div class="col-md-6">
                        <div class="card border p-3 text-center">

                            <h5>General Settings</h5>
                            <p class="text-muted">Website basic information</p>

                            <a href="{{ route('admin.settings.general') }}"
                               class="btn btn-dark">
                                Open General Settings
                            </a>

                        </div>
                    </div>

                    {{-- PAYMENT SETTINGS --}}
                    <div class="col-md-6">
                        <div class="card border p-3 text-center">

                            <h5>Payment Settings</h5>
                            <p class="text-muted">Payment gateways & numbers</p>

                            <a href="{{ route('admin.settings.payment') }}"
                               class="btn btn-success">
                                Open Payment Settings
                            </a>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection