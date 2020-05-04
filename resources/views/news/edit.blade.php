@extends('layouts.app')

@section('title', 'Article: #'. $article->id)

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-6">
            <h3>
                <a class="btn" href="{{ route('news.index') }}">
                    <i class="fas fa-chevron-left">
                    </i>
                </a>
                Edit Article
            </h3>
        </div>
        <div class="col-6 text-right">
        	<form action="{{ route('news.show', $article->id) }}" method="POST">
        		@csrf
        		@method('DELETE')
	        	<button class="btn btn-outline-danger float-right ml-2" type="submit">
	        		<i class="fas fa-trash-alt">
	                </i>
	                {{ __('Delete') }}
	        	</button>
        	</form>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('news.show', $article->id) }}" method="POST">
            @csrf
			@method('PATCH')
            <input name="id" type="hidden" value="{{ $article->id }}"/>
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right" for="title">
                    {{ __('Title') }}
                </label>
                <div class="col-md-6">
                    <input autocomplete="title" autofocus="" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required="" type="text" value="{{ old('title', $article->title) }}" />
                    @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>
                            {{ $message }}
                        </strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right" for="text">
                    {{ __('Text') }}
                </label>
                <div class="col-md-6">
                    <textarea class="form-control @error('text') is-invalid @enderror" name="text">{{ old('text', $article->text) }}</textarea>
                    @error('text')
                    <span class="invalid-feedback" role="alert">
                        <strong>
                            {{ $message }}
                        </strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right" for="state">
                    {{ __('State') }}
                </label>
                <input name="state" type="hidden" value="new"/>
                <div class="col-md-6">
                    {!! Form::select(
							'state',
							array_combine(
								$states, 
								array_map(function($v){return ucfirst($v);}, $states)
							),
							old('state', $article->state),
							[
								'class' => 'form-control', 
								//'disabled' => true
							]
						); !!}


					@error('state')
                    <span class="invalid-feedback" role="alert">
                        <strong>
                            {{ $message }}
                        </strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button class="btn btn-primary" type="submit">
                        {{ __('Send') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
