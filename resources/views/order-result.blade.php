<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Order Result</title>
    <style>
        body { font-family: Arial, sans-serif; direction: ltr; padding: 20px; }
        pre { background: #f5f5f5; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>CJ Order Submission Result</h1>

    @if(isset($response))
        <pre>{{ json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    @elseif(isset($error))
        <div style="color:red; background:#ffe5e5; padding:10px; border-radius:5px;">
            {{ $error }}
        </div>
        <a href="{{ url('/cj/send-order') }}">Go Back and Try Again</a>
    @else
        <p>Click the button below to send a test order.</p>
        <form method="POST" action="{{ url('/cj/send-order') }}">
            @csrf
            <button type="submit">Send Order</button>
        </form>
    @endif
</body>
</html>