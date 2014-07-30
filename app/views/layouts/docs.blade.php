@extends('layouts.master')

@section('content')
<header id="header" role="header">
    <div class="boxed">
        <div id="tagline">
            <h1>Documentation.</h1>
        </div>

        <div id="version">
            <ul class="nolist">
                @if (DOCS_VERSION == 'master')
                    <li class="current"><a href="{{ url('docs/dev') }}" title="Dev">Dev</a></li>
                    <li><a href="{{ url('docs/4-2') }}" title="4.2">4.2</a></li>
                    <li><a href="{{ url('docs/4-1') }}" title="4.1">4.1</a></li>
                    <li><a href="{{ url('docs/4-0') }}" title="4.0">4.0</a></li>
                @elseif (DOCS_VERSION == '4.2')
                    <li><a href="{{ url('docs/dev') }}" title="Dev">Dev</a></li>
                    <li class="current"><a href="{{ url('docs/4-2') }}" title="4.2">4.2</a></li>
                    <li><a href="{{ url('docs/4-1') }}" title="4.1">4.1</a></li>
                    <li><a href="{{ url('docs/4-0') }}" title="4.0">4.0</a></li>
                @elseif (DOCS_VERSION == '4.1')
                    <li><a href="{{ url('docs/dev') }}" title="Dev">Dev</a></li>
                    <li><a href="{{ url('docs/4-2') }}" title="4.2">4.2</a></li>
                    <li class="current"><a href="{{ url('docs/4-1') }}" title="4.1">4.1</a></li>
                    <li><a href="{{ url('docs/4-0') }}" title="4.0">4.0</a></li>
                @else
                    <li><a href="{{ url('docs/dev') }}" title="Dev">Dev</a></li>
                    <li><a href="{{ url('docs/4-2') }}" title="4.2">4.2</a></li>
                    <li><a href="{{ url('docs/4-1') }}" title="4.1">4.1</a></li>
                    <li class="current"><a href="{{ url('docs/4-0') }}" title="4.0">4.0</a></li>
                @endif
            </ul>
        </div>
    </div>
</header>

<nav id="primary">
    <div class="boxed">
        <div id="logo-head">
            <a href="//laravel.com"><img src="../assets/img/logo-head.png" alt="Laravel"></a>
        </div>
        <ul>
            <li><a href="/">Welcome</a></li>
            <li><a href="https://forge.laravel.com">Hosting</a></li>
            <li class="current-item"><a href="docs" title="Documentation">Documentation</a></li>
            <li><a href="{{ url('api') }}/{{ DOCS_VERSION }}" title="Laravel Framework API">API</a></li>
            <li><a href="https://github.com/laravel/laravel" title="Github">Github</a></li>
            <li><a href="http://laravel.io/forum" title="Laravel Forums">Forums</a></li>
            <li><a href="http://twitter.com/laravelphp" title="Laravel on Twitter">Twitter</a></li>
        </ul>
    </div>
</nav>

<div id="content">

    <section id="documentation">
        <article class="boxed">

            <nav id="docs">
                {{ $index }}
            </nav>

            <div id="docs-content">
                {{ $contents }}
            </div>

        </article>
    </section>

</div>

<footer id="foot" class="textcenter">
    <div class="boxed">

        <nav id="secondary">
            <div id="logo-foot">
                <a href="//laravel.com"><img src="../assets/img/logo-foot.png" alt="Laravel"></a>
            </div>
            <ul>
                <li><a href="/">Welcome</a></li>
                <li><a href="https://forge.laravel.com">Hosting</a></li>
                <li class="current-item"><a href="docs" title="Documentation">Documentation</a></li>
                <li><a href="api/{{ DOCS_VERSION }}" title="Laravel Framework API">API</a></li>
                <li><a href="https://github.com/laravel/laravel" title="Github">Github</a></li>
                <li><a href="http://laravel.io/forum" title="Laravel Forums">Forums</a></li>
                <li><a href="http://twitter.com/laravelphp" title="Laravel on Twitter">Twitter</a></li>
            </ul>
        </nav>

    </div>
</footer>

<div id="top">
    <a href="#index" title="Back to the top">
        <i class="icon-chevron-up"></i>
    </a>
</div>

@endsection
