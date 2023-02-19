@extends('Layouts.dashboard')

@section('title', 'Categories')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
@endsection

@section('content')

   <form method="post" action="{{route('dashboard.categories.store')}}" enctype="multipart/form-data">
       @csrf

       @include('dashboard.categories._form' ,['button' => 'create'])
   </form>
@endsection

