<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            line-height: 1.5;
        }

        h1,h2,h3 {
            margin: 0;
        }

        .header {
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .section {
            margin-bottom: 15px;
        }

        .muted {
            color: #666;
        }

        ul {
            margin: 5px 0 0 15px;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>{{ $resume['name'] }}</h1>
    <div class="muted">
        {{ $resume['title'] }}
    </div>

    <div class="muted">
        {{ $resume['email'] }} •
        {{ $resume['phone'] }} •
        {{ $resume['location'] }}
    </div>
</div>

<div class="section">
    <h3>Professional Summary</h3>
    <p>{{ $resume['summary'] }}</p>
</div>

<div class="section">
    <h3>Skills</h3>

    @foreach($resume['skills'] as $skill)
        <span>{{ $skill }}, </span>
    @endforeach
</div>

<div class="section">
    <h3>Experience</h3>

    @foreach($resume['experience'] as $exp)
        <strong>{{ $exp['job_title'] }}</strong><br>
        <span class="muted">
            {{ $exp['company'] }} • {{ $exp['location'] }} • {{ $exp['dates'] }}
        </span>

        <ul>
            @foreach($exp['responsibilities'] as $r)
                <li>{{ $r }}</li>
            @endforeach
        </ul>
    @endforeach
</div>

@if(!empty($resume['education']))
<div class="section">
    <h3>Education</h3>

    @foreach($resume['education'] as $edu)
        <strong>{{ $edu['qualification'] }}</strong><br>
        <span class="muted">
            {{ $edu['institution'] }} • {{ $edu['dates'] }}
        </span>
    @endforeach
</div>
@endif

</body>
</html>
