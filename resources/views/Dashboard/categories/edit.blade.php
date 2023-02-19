@extends('Layouts.dashboard')

@section('title', 'Edit Category')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
    <li class="breadcrumb-item active">Edit Category</li>
@endsection

@section('content')

   <form method="post" action="{{route('dashboard.categories.update' , $category->id)}}" enctype="multipart/form-data">
       @csrf
       @method('put')

       @include('dashboard.categories._form' ,['button' => 'update'])
   </form>
@endsection

