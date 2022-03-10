@php

	$delayAttr = isset($delay) ? ('data-delay="'.$delay.'"') : '';
@endphp

<div class="alert-container" {!! $delayAttr !!}>
	@if (Session::has('success') || Session::has('status'))
		<div class="alert alert-success animated fadeInDown">
			<span class="icon"><i class="fa fa-check" aria-hidden="true"></i></span>
			<span class="message">{!! Session::get('success')?Session::get('success'):Session::get('status') !!}</span>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			
		</div>
	@endif
	
	@if (Session::has('error'))
		<div class="alert alert-danger animated fadeInDown">
			<span class="icon"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
			<span class="message">{!! Session::get('error') !!}</span>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			
		</div>
	@endif
	
	@if (count($errors) > 0)
	
		<div class="alert alert-danger animated fadeInDown">
			<span class="icon"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></span>
            <span class="message">{!! $errors->all()[0] !!}</span>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		</div>
	@endif
</div>