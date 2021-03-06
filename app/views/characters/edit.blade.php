@extends('public.master')

@section('content')
	{{ Form::open(array('url' => URL::route('character.update', array('name' => $character->name)), 'method' => 'PUT')) }}
		<div class='character-data'>
			<header>
				<h2>{{{ trans('character.headers.character-data') }}}</h2>
			</header>
			<div>
				<span class='title'>{{{ trans('character.spans.name') }}}:</span>
				<span class='value'>{{{ $character->name }}}</span>
			</div>
			<div>
				<span class='title'>{{{ trans('character.spans.sex') }}}:</span>
				<span class='value'>{{{ Config::get('zenith.sexes')[$character->sex] }}}</span>
			</div>
			@if (count(Config::get('zenith.worlds')))
				<div>
					<span class='title'>{{{ trans('character.spans.world') }}}:</span>
					<span class='value'>Forgotten</span>
				</div>
			@endif
			<div>
				<span class='title'>{{{ trans('character.spans.last-login') }}}:</span>
				<span class='value'>{{{ $character->lastlogin ? date(Config::get('zenith.long_datetime_format'), $character->lastlogin) : trans('character.spans.never-logged-in') }}}</span>
			</div>
		</div>
	
		<div class='character-info'>
			<header>
				<h2>{{{ trans('character.headers.character-info') }}}</h2>
			</header>
			<div>
				<span class='title'>{{{ trans('character.forms.hidden') }}}:</span>
				<span class='value'>
					{{ Form::checkbox('is_hidden', 'hide-character', $character->is_hidden) }} {{{ trans('character.forms.hidden-info') }}}
				</span>
			</div>
			<div>
				<span class='title'>{{{ trans('character.forms.comment') }}}:</span>
				<span class='value'>{{ Form::textarea('comment', $character->comment) }}</span>
			</div>
		</div>
		
		<div class='submit'>
			{{ Form::submit(trans('character.forms.submit'), array('class' => 'create-character')) }}
		</div>
	{{ Form::close() }}
@stop
