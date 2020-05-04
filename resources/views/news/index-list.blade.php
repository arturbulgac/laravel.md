<table class="table table-light table-bordered table-hover table-striped">
	<thead class="thead-dark">
		<th style="width: 80px;">
			#
		</th>
		<th>
			Title
		</th>
		<th style="width: 120px;">
			Created at
		</th>
		<th style="width: 120px;">
			AJAX act's
		</th>
	</thead>
	<tbody id="tbody-news">
		@if($articles->count())
			@foreach($articles as $item)
		<tr {{-- data-json='@json($item)' --}} data-id="{{ $item->id }}">
			<td>
				<a href="news/{{ $item->id }}">{{ $item->id }}</a>
			</td>
			<td data-for="title">
				{{ $item->title }}
			</td>
			<td style="width: 120px;" class="text-center" data-for="created_at">
				{{ $item->created_at }}
			</td>
			<td class="text-center">
				<a class="btn btn-outline-primary" href="{{ route('news.edit', $item->id) }}" data-target="#modal-editArticle" data-toggle="modal" role="button">
					<i class="fas fa-edit">
					</i>
				</a>
				<button type="button" onclick="article_remove({{ $item->id }})" class="btn btn-outline-danger">
					<i class="fas fa-trash-alt">
					</i>
				</button>
			</td>
		</tr>
		@endforeach
		@else
		<tr>
			<td class="text-center" colspan="6">
				There are no items
			</td>
		</tr>
		@endif
	</tbody>
</table>
{{ $articles->links() }}