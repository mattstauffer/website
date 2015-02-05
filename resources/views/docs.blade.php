@extends('app')

@section('content')
<nav id="slide-menu" class="slide-menu" role="navigation">
	
	<div class="brand">
		<a href="/">
			<img src="/assets/img/laravel-logo-white.png" height="50">
		</a>
	</div>

	<ul class="slide-main-nav">
		<li><a href="/">Home</a></li>
		@include('partials.main-nav')
	</ul>

	<div class="slide-docs-nav">
		<h2>Documentation</h2>
		@include('partials.search-form')
		{!! $index !!}
	</div>

</nav>

<div class="docs-wrapper container">

	<section class="sidebar">
		@include('partials.search-form')
		{!! $index !!}
	</section>

	<article>
		{!! $content !!}
	</article>
</div>
@endsection