@extends('layouts.app')

@section('title', 'News')

@section('styles')
<style scoped="">
	.pagination { justify-content: center !important; }
	textarea {
		min-height: 50px;
		max-height: 300px;
	}
</style>
@endsection

@section('content')
<div class="container">
	<div class="row mb-2">
		<div class="col-6">
			<h3>
				News:
			</h3>
		</div>
		<div class="col-6 text-right">
			<a class="btn btn-outline-primary float-right ml-2" data-target="#modal-addArticle" data-toggle="modal" href="{{ route('news.create') }}" role="button">
				<i class="fas fa-plus">
				</i>
				{{ __('Add') }}
			</a>
			

			<form action="" method="GET" class="d-inline-block" id="filter-form">
                <div data-class="form-group row">
                    <div class="input-group mb-3">
                         <input type="text" placeholder="Search by: title" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', Request::query('title')) }}" autocomplete="off" autofocus>
                        <div class="input-group-append">
                            
                            <div class="dropdown-menu" onchange="event.target.form.submit()">
                                <div class="form-check dropdown-item">
                                    <input class="form-check-input invisible" type="radio" name="state" id="radio-state-any" value="" {{ Request::query('state') && in_array(Request::query('state'), $states, true) ? '' : 'checked' }}>
                                    <label class="form-check-label d-block" for="radio-state-any">
                                        Any
                                    </label>
                                </div>
                                <div role="separator" class="dropdown-divider"></div>
                                @foreach($states as $state)
                                <div class="form-check dropdown-item">
                                    <input class="form-check-input invisible" type="radio" name="state" id="radio-state-{{ $state }}" value="{{ $state }}" {{ Request::query('state') === $state ? 'checked' : '' }}>
                                    <label class="form-check-label d-block" for="radio-state-{{ $state }}">
                                        {{ucfirst($state)}}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <button class="btn btn-outline-secondary dropdown-toggle rounded-0 border-right-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Request::query('state') && in_array(Request::query('state'), $states, true) ? Request::query('state') : 'Any' }}</button>
                            <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </form>
		</div>
	</div>
	<div id="ajax-content">@include('news.index-list')</div>
</div>

<!-- MODALS -->
<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal-addArticle" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					New article
				</h5>
				<button aria-label="Close" class="close" data-dismiss="modal" type="button">
					<span aria-hidden="true">
						×
					</span>
				</button>
			</div>
			<form action="{{ route('news.index') }}" method="POST" onsubmit="newsOnSubmitFormAdd(this); return false;">
				@csrf
				<div class="modal-body">
					<div class="form-group">
						<label class="col-form-label" for="add-article-title">
							Title:
						</label>
						<input class="form-control" id="add-article-title" name="title" type="text"/>
						<span class="invalid-feedback"></span>
					</div>
					<div class="form-group">
						<label class="col-form-label" for="add-article-text">
							Text:
						</label>
						<textarea class="form-control" id="add-article-text" name="text"></textarea>
						<span class="invalid-feedback"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-secondary" data-dismiss="modal" type="button">
						Close
					</button>
					<button class="btn btn-primary" type="submit">
						Add
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="modal-editArticle" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					Edit article
				</h5>
				<button aria-label="Close" class="close" data-dismiss="modal" type="button">
					<span aria-hidden="true">
						×
					</span>
				</button>
			</div>
			<div class="ajax-content">-----</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
window.addEventListener('load', function() {
	$('#filter-form').on('submit', function(e){
		e.preventDefault();
		$('#ajax-content').empty().append('<h3 class="text-center">LOADING...</h3>');
		try {
			history.replaceState(null, document.head.title, '?' + $(this).serialize());
		} catch(e) {
			console.log('incompatible browser');
		}
		
		$.get(this.action,$(this).serialize()).fail(function(){
			$('#ajax-content').empty().append('<h3 class="text-center">Error :/</h3>');
		}).done(function(html){
			$('#ajax-content').empty().append(html);
		});
		//skip errors
	});


	$('#modal-addArticle').on('hide.bs.modal', function(){
		if (typeof this._xhr !== "undefined") {
			this._xhr.abort();
			delete this._xhr;
		}

		var $form = $(this).find('form');
		$form.find('.invalid-feedback').removeClass('.d-block').empty();
		$form[0].reset();
	});

    $('#modal-editArticle').on('show.bs.modal', function (event) {
    	// v1. deprecated. data can be outdated
    	/*var data = $(event.relatedTarget).closest('tr').data('json');
		var $form = $(this).find('.modal-content > form');

		$form.find('[name="title"]').val(data.title);
		$form.find('[name="text"]').val(data.text);
		...
		*/

		// v2. hybrid
		var $content = $(this).find('.ajax-content').empty().append('<h3 class="text-center">LOADING...</h3>');
		// post '/ajax/news/'+$(event.relatedTarget).data('id')+'/edit'
		this._xhr = $.get('/news/'+$(event.relatedTarget).closest('tr').data('id')+'/edit', {
			"_token": "{{ csrf_token() }}"
		}).always(function(){
			delete this._xhr;
		}.bind(this)).fail(function(){
			$content.empty().append('<h3 class="text-center">Error :/</h3>');
		}.bind($content)).done(function(html){
			this.empty().append(html);
		}.bind($content));
	}).on('hide.bs.modal', function(){
		if (typeof this._xhr !== "undefined") {
			this._xhr.abort();
			delete this._xhr;
		}
	});
});

function newsOnSubmitFormEdit(form) {
	$(form).find('.invalid-feedback').removeClass('.d-block').empty();
	
	$('#modal-editArticle')[0]._xhr = $.post(form.action, $(form).serialize()).always(function(){
		delete $('#modal-editArticle')[0]._xhr;
	}).fail(function(xhr){
		if (xhr.status == 0) { //aborted
			// do nothing
		} else if(xhr.status === 422 && xhr.responseJSON) {
			for(var i in xhr.responseJSON.errors) {
				this.find('[name="'+i+'"] ~ .invalid-feedback').html('<strong>'+xhr.responseJSON.errors[i][0]+'</strong>').addClass('d-block');
			}
		} else {
			alert("Fail with xhr.status: " + xhr.status);
		}
	}.bind($(form))).done(function(json){
		$('#modal-editArticle').modal('hide');
		article_updateRow(json);
	});
};

function newsOnSubmitFormAdd(form) {
	$(form).find('.invalid-feedback').removeClass('.d-block').empty();
	
	$('#modal-addArticle')[0]._xhr = $.post(form.action, $(form).serialize()).always(function(){
		delete $('#modal-addArticle')[0]._xhr;
	}).fail(function(xhr){
		if (xhr.status == 0) { //aborted
			// do nothing
		} else if(xhr.status === 422 && xhr.responseJSON) {
			for(var i in xhr.responseJSON.errors) {
				this.find('[name="'+i+'"] ~ .invalid-feedback').html('<strong>'+xhr.responseJSON.errors[i][0]+'</strong>').addClass('d-block');
			}
		} else {
			alert("Fail with xhr.status: " + xhr.status);
		}
	}.bind($(form))).done(function(json){
		$('#modal-addArticle').modal('hide');

		// v1 --- confusing when have pagination Or was added some items by other session
		//article_prependRow(json); // just a demo
		
		// v2. i will refresh list on each time after successfully created some article
		$('#filter-form')[0].submit(); // will reload list!!
	});
}

function article_prependRow(article) {
	
	var dateFormat = function(strDate) {
		var date = new Date(strDate);
		var zeroFill = function(int){
			if (int > 9) {
				return int;
			} else {
				return '0' + int;
			}
		};
		return date.getFullYear() + '-' + zeroFill(date.getMonth() + 1) + '-' + zeroFill(date.getDate()) 
			+ ' ' + zeroFill(date.getHours()) + ':' + zeroFill(date.getMinutes()) + ':' + zeroFill(date.getSeconds()); 
	};

	var row = $('#tbody-news').prepend(
		'<tr data-id="'+article.id+'" class="text-success">\
			<td>\
				<a href="news/'+article.id+'">'+article.id+'</a>\
			</td>\
			<td data-for="title">'+article.title+'</td>\
			<td style="width: 120px;" class="text-center" data-for="created_at">'+dateFormat(article.created_at)+'</td>\
			<td class="text-center">\
				<a class="btn btn-outline-primary" href="/news/'+article.id+'/edit" data-target="#modal-editArticle" data-toggle="modal" role="button">\
					<i class="fas fa-edit"></i>\
				</a>\
				<button type="button" onclick="article_remove(19)" class="btn btn-outline-danger">\
					<i class="fas fa-trash-alt"></i>\
				</button>\
			</td>\
		</tr>'
	);
}

function article_updateRow(article) {
	var row = $('#tbody-news').children('[data-id="'+article.id+'"]');
	
	if (row.length != 1) {
		return; // skip
	}

	//update title
	row.children('[data-for="title"]').text(article.title);
}

function article_remove(id) {
	$.post("{{ route('news.index') }}/" + id,{
		"_token": "{{ csrf_token() }}",
		"_method": "delete"
	}).always(function(){

	}).fail(function(xhr){
		if (xhr.status == 0) { //aborted
			// do nothing
		} else if(xhr.status === 422 && xhr.responseJSON) {
			alert("Probably you just tryiong to hack something... ]:)\nThis error can not be showed in normal case");
		} else {
			alert("Fail with xhr.status: " + xhr.status);
		}
	}).done(function(json){
		/*
		// v1. can be an empty list with pagination
		var row = $('#tbody-news').children('[data-id="'+json.id+'"]');
	
		if (row.length != 1) {
			return; // skip
		}

		row.remove();
		*/

		// v2. i will refresh list on each time after successfully removed some article
		console.log('v2. i will refresh list on each time after successfully removed some article');
		$('#filter-form')[0].submit();
	});
}

</script>
@endsection
