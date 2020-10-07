<!-- * Extension du layout parent * -->
@extends('partials.base-layout')



<!-- * Contenu * -->

@section('layout-content')

<div class="project-form box">
    <div class="project-form__header">
        <h1 class="project-form__title title">Je poste mon projet</h1>
    </div>
    <hr>
    <div class="project-form__content">
        <form action="{{route('projects.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- * Titre * -->

            <div class="field">
                <label class="label">Propose un titre de projet</label>
                <div class="control">
                    <input class="input @error('title') is-danger @enderror is-rounded" name="title" id="project_title" maxlength="80" type="text" placeholder="Titre de ton projet" value="{{old('title')}}">
                </div>
                @error('title')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
            <hr>
            <!-- * Catégories * -->

            <div class="field">
                <label class="label">Choisis une catégorie</label>
                <div class="project-form__categories-list">
                    @foreach($categories as $category)
                    <label class="categories-list__category-label box__label--category label">
                        <input class="radio" type="radio" name="category" value="{{$category->id}}">
                        <span class="categories-list__category-name label-name">{{$category->name}}</span>
                    </label>
                    @endforeach
                </div>
                @error('category')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
            <hr>

            <!-- * Niveau de difficultés * -->

            <div class="field">
                <label class="label">Choisis la difficulté du projet</label>
                <div class="project-form__difficulty-levels-list">
                    @foreach($difficultyLevels as $difficultyLevel)
                    <label class="label">
                        <input class="radio" type="radio" name="difficulty_level" value="{{$difficultyLevel->id}}">
                        <span class="label-name">{{$difficultyLevel->name}}</span>
                    </label>
                    @endforeach
                </div>
                @error('difficulty_level')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
            <hr>

            <!-- * Thumbnail * -->

            <div class="thumbnail-field-box field">
                <label for="thumbnail" class="label">Télecharge une image thumbnail</label>
                <label class="thumbnail-field-box thumbnail-field-box__label thumbnail-field-box__label--thumbnail  box__label--image label" for="thumbnail"></label>
                <div class="control">
                    <input class="thumbnail-field-box__thumbnail-file-input box__input box__input--file input" type="file" id="thumbnail" name="thumbnail">
                </div>
                @error('thumbnail')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <hr>

            <!-- * Matériels * -->

            <div class="materials-fields-box">
                <div class="materials-fields-box__fields-box field">
                    <label class="label">Liste les matériels nécessaires pour ce projet</label>
                    @if(old('material'))
                    @php
                    $defaultInputsNumber = 1;
                    $inputsNumber = count(old('material'));
                    @endphp
                    @if($defaultInputsNumber <= $inputsNumber)
                    @foreach(old('material') as $key => $value)
                    <div class="materials-fields-box__material-box control">
                        <span class="materials-fields-box__dot">&#x25CF;</span>
                        <input class="input @error('material') is-danger @enderror is-rounded" type="text" placeholder="ex: 2 palettes" name="material[]" id="{{$defaultInputsNumber++}}" value="{{$value}}">
                    </div>
                    @endforeach
                    @endif
                    @else
                    <div class="materials-fields-box__material-box control">
                        <span class="materials-fields-box__dot">&#x25CF;</span>
                        <input class="input @error('material') is-danger @enderror is-rounded" type="text" placeholder="ex: 2 palettes" name="material[]" id="">
                    </div>
                    @endif

                    @error('material.*')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>
                <button class="materials-fields-box__button button is-rounded" type="button">
                    <i class="materials-fields-box__icon fa fa-plus" aria-hidden="true"></i>
                    ajouter du matériel
                </button>
            </div>
            <hr>

            <!-- * Durée  * -->

            <div class="time-fields-box">
                <div class="field">
                    <label class="label">Indique la durée estimée du projet</label>
                    <small>vous pouvez exprimer la durée de votre projet en heure ou en minute. Par défault, il sera en minute.</small>
                    <div class="time-fields-box__d-flex">
                        <div class="control">
                            <input class="input @error('duration') is-danger @enderror is-rounded" type="text" placeholder="2" name="duration" value="{{old('duration')}}">
                        </div>
                        <div class="time-select select is-rounded">
                            <select name="unity_of_measurement">
                                @foreach($unities as $unity)
                                <option value="{{$unity->id}}">{{$unity->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @error('duration')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
            <hr>

            <!-- * Budget * -->

            <div class="budget-fields-box">
                <div class="field">
                    <label class="label">Indique le budget estimé pour ce projet</label>
                    <div class="budget-fields-box__d-flex">
                        <div class="control">
                            <input class="input @error('budget') is-danger @enderror is-rounded" name="budget" type="text" min="0" max="24" placeholder="2" value="{{old('budget')}}">
                        </div>
                        <i class="fa fa-eur" aria-hidden="true"></i>
                    </div>
                </div>
                @error('budget')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
            <hr>

            <!-- * Contenu  du projet * -->

            <div class="project-form__sub-content">
                <label class="label">Ecris le contenu de ton projet</label>
                <small class="project-form__advice">conseil: si tu souhaites du texte après l'ajout de ton image, pense à appuyer sur la touche "Entrer" pour revenir à la ligne.</small>
                <div class="project-form__summernote-box field">
                    <textarea class="summernote" name="content">{{old('content')}}</textarea>
                    @error('content')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- *  Footer * -->

            <div class="project-form__sub-footer">
                <button class="project-form__button button is-rounded" type="submit" name="submit" value="draft">
                    <span>ajouter au brouillon</span>
                </button>
                <button class="project-form__button project-form__button--submit button is-rounded" type="submit" name="submit" value="publish">
                    <span>poster mon projet !</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection