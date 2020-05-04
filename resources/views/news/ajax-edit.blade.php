<form action="/news/{{ $article->id }}" method="POST" onsubmit="newsOnSubmitFormEdit(this); return false;">
    @csrf
	@method('PATCH')
    <input name="id" type="hidden" value="{{ $article->id }}"/>
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right" for="title">
            {{ __('Title') }}
        </label>
        <div class="col-md-6">
            <input autocomplete="title" autofocus="" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required="" type="text" value="{{ old('title', $article->title) }}" />
                
            <span class="invalid-feedback"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-md-right" for="text">
            {{ __('Text') }}
        </label>
        <div class="col-md-6">
            <textarea class="form-control @error('text') is-invalid @enderror" name="text">{{ old('text', $article->text) }}</textarea>
            
            <span class="invalid-feedback"></span>
            
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
			
            <span class="invalid-feedback"></span>
            
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-4">
        	<button class="btn btn-secondary" data-dismiss="modal" type="button">
				Close
			</button>
            <button class="btn btn-primary" type="submit">
                {{ __('Send') }}
            </button>
        </div>
    </div>
</form>