<!DOCTYPE html>
<html>
    <head>
        <title>Poll Results</title>
    </head>
    <body>
        <h1>Poll Results</h1>
        @foreach ($results as $question => $options)
            <h2>{{ $question }}</h2>
            @foreach ($options as $option => $data)
                <p>{{ $option }}: {{ $data['count'] }}</p>
            @endforeach
        @endforeach
    </body>
</html>
