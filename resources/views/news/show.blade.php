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
				Article
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
	        	<a class="btn btn-outline-primary float-right ml-2" href="{{ route('news.edit', $article->id) }}">
					<i class="fas fa-pencil">
					</i>
					{{ __('Edit') }}
				</a>
        	</form>
			
		</div>
	</div>
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">
				{{ $article->title }}
			</h5>
			<h6 class="card-subtitle mb-2 text-muted">
				Author: {{ $article->author->name }}
			</h6>
			<hr>
				<p class="card-text">
					{{ $article->text }}
				</p>
				<hr>
					<div class="row">
						<div class="col-6">
							<h6 class="card-subtitle mb-2 text-muted">
								Created at: {{ $article->created_at }}
							</h6>
						</div>
						<div class="col-6">
							<h6 class="card-subtitle mb-2 text-muted">
								Last updated at: {{ $article->updated_at }}
							</h6>
						</div>
					</div>
				</hr>
			</hr>
		</div>
	</div>
</div>
@endsection
