<!DOCTYPE html>
<html>
<head>
    <title>CJ Auth Complete</title>
</head>
<body>
<script>
    if (window.opener) {
        window.opener.postMessage(
            { status: "{{ $status }}" },
            window.location.origin
        );
        window.close();
    } else {
        // fallback if user opened callback directly
        window.location.href = "/";
    }
</script>
</body>
</html>