<h1>Search results for term "{{{ $keyword }}}"</h1>

@if (count($hits) > 0)
    <ul>
        @foreach ($hits as $hit)
            <li><a href="{{ url('docs/' . $hit['_source']['slug']) }}/">{{ $hit['_source']['title'] }}</a>
                (Score: {{ sprintf("%01.2f", $hit['_score'] * 100 / $totalScore) }})
            </li>
        @endforeach
    </ul>
@else
    <p>No search results found.</p>
@endif
