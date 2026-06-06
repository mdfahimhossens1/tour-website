@extends('layouts.admin')
@section('title','Add Payment Method')
@section('page')

<div class="container">

<form method="POST" action="{{ route('admin.payment_methods.store') }}">
@csrf

<div class="card p-3">

    <input type="text" name="name" class="form-control mb-2" placeholder="Method Name">

    <input type="text" name="type" class="form-control mb-2" placeholder="Type (bkash, bank, stripe)">

    <input type="text" name="account_number" class="form-control mb-2" placeholder="Account Number">

    <input type="text" name="api_key" class="form-control mb-2" placeholder="API Key">

    <input type="text" name="secret_key" class="form-control mb-2" placeholder="Secret Key">

    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>

    <button class="btn btn-primary">Save</button>

</div>

</form>

</div>

@endsection