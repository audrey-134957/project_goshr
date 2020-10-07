@extends('partials.admin-base-layout')

@section('admin-title', 'catégories')

@section('admin-header-subtitle', 'catégories')

@section('layout-content')
<div class="category-creation-box box">
    <label class="category-creation-box__label label">Créer une nouvelle catégorie</label>
    <form action="{{route('admin.storeCategory', ['adminId' => auth()->user()->id])}}" method="post" style="display:flex;">
        @csrf
        <div class="category-creation-box__input-box section__field field">
            <div class="control">
                <input class="category-creation-box__category-field section__input input is-rounded" name="category_name" value="{{old('category_name')}}" type="text" placeholder="nom de la catégorie">
            </div>
        </div>
        <button class="category-creation-box__submit-button button is-rounded" type="submit">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </button>
    </form>
    @error('category_name')
    <p class="help is-danger">{{ $message }}</p>
    @enderror
</div>

@foreach($categories as $category)
<div class="category-card card">
    <div class="category-card__content">
        <span>{{$category->name}}</span>
        <div>
            <button class="category-card__edit-button button is-rounded" type="button">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </button>
            <button class="category-card__delete-button modal-button button is-rounded" type="button">
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </button>
            @include('admins.partials.modals.deletion.category.admin-modal')
        </div>
    </div>
    <form action="{{route('admin.updateCategory', ['adminId' => auth()->user()->id, 'category' => $category->slug])}}" method="POST" class="category-card__edit-form form--edit is-hidden">
        @csrf
        @method('PATCH')
        <div class="category-card__input-box section__field field">
            <div class="control">
                <input class="section__input input is-rounded" type="text" name="edit_category_name" placeholder="nom de la catégorie" value="{{$category->name}}">
            </div>
        </div>
        <button class="category-card__sd-edit-button button is-rounded" type="submit">
            modifier
        </button>
    </form>
</div>
@endforeach
@endsection