<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TEST: reading json</title>
</head>
<body>
    
    {{-- @dd($response_json["response"]["results"][0]["webTitle"]); --}}

    @foreach ($response_json["response"]["results"] as $article)
        <p><h5>{{$article["webPublicationDate"]}}</h5> <h3>{{$article["webTitle"]}}</h3></p>
        <hr>
        
    @endforeach

</body>
</html>