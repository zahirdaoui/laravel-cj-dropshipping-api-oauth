<!DOCTYPE html>
<html>
<head>
    <title>CJ User Info</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>User Info</h1>
    <p><strong>Name:</strong> {{ $data['data']['openName'] }}</p>
    <p><strong>Email:</strong> {{ $data['data']['openEmail'] }}</p>
    <p><strong>User Number:</strong> {{ $data['data']['userNum'] }}</p>
    <p><strong>Open ID:</strong> {{ $data['data']['openId'] }}</p>

    <h2>Quota Limits</h2>
    <table>
        <tr>
            <th>API Endpoint</th>
            <th>Doc Link</th>
            <th>Quota Limit</th>
            <th>Requested</th>
        </tr>
        @foreach($data['data']['setting']['quotaLimits'] as $quota)
        <tr>
            <td>{{ $quota['quotaUrl'] }}</td>
            <td><a href="{{ $quota['docLink'] }}" target="_blank">Docs</a></td>
            <td>{{ $quota['quotaLimit'] }}</td>
            <td>{{ $quota['requestedNum'] }}</td>
        </tr>
        @endforeach
    </table>

    <p><strong>QPS Limit:</strong> {{ $data['data']['setting']['qpsLimit'] }}</p>

    <h2>Callbacks</h2>
    @foreach($data['data']['callback'] as $type => $cb)
        <p>{{ ucfirst($type) }}: {{ $cb['type'] }}</p>
    @endforeach

    <p><strong>Sandbox Mode:</strong> {{ $data['data']['isSandbox'] ? 'Yes' : 'No' }}</p>
</body>
</html>