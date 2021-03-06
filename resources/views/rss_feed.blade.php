<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        {{-- CHANNEL elements --}}
        <title><![CDATA[The Guardian RSS Feed News Section]]></title>
        <link>{{$response_json["response"]["section"]["webUrl"]}}</link>
        <description>Get the last RSS Feed news from the reknowed UK newspaper The Guardian, by choosing your favourite section.</description>
        <language>en</language>
        <category>{{$response_json["response"]["section"]["webTitle"]}}</category>
        <copyright>2021 &#169; The Guardian News &#38; Media Limited or its affiliated companies</copyright>
        <docs>https://validator.w3.org/</docs>
        {{-- <atom:link href="{{$response_json["response"]["section"]["webUrl"]}}" rel="self" type="application/rss+xml" /> --}}
        @foreach ($response_json["response"]["results"] as $article)
        <item>
            {{-- ITEM elements --}}
            <title><![CDATA[{{$article["webTitle"]}}]]></title>
            <link>{{$article["webUrl"]}}</link>
            <category>{{$article["sectionName"]}}</category>
            <guid isPermaLink="true">{{$article["webUrl"]}}</guid>
            {{-- Converting API date format in RSS date format --}}
            <pubDate>{{ date('D, d M Y H:i:s +0000', strtotime($article["webPublicationDate"])) }}</pubDate>
        </item>
        @endforeach
    </channel>
</rss>