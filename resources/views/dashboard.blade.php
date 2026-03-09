<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if(session('success'))
                    <div class="alert alert-success w-50">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger w-50">
                        {{ session('error') }}
                    </div>
                @endif
                 <a href="{{ route('cj.redirect') }}" 
                        style="background-color:#FF7700;"
                        class="inline-flex items-center px-6 py-3  text-white font-bold rounded-full transition duration-300 transform hover:scale-105 shadow-lg">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke="white" stroke-width="2"/>
                            </svg>
                         Connect CJ
                </a>
            </br>
                <button   style="background-color:#FF7700;"  
                        class="mt-5 inline-flex items-center px-6 py-3  text-white font-bold rounded-full transition duration-300 transform hover:scale-105 shadow-lg" 
                        onclick="connectCJ()">
                        Connect CJ POP UP PAGE
                </button>
                <form action="{{ route('cj.logout') }}" method="POST">
                    @csrf
                    <button type="submit"  style="background-color:#f90800;" class="mt-5 inline-flex items-center px-6 py-3  text-white font-bold rounded-full transition duration-300 transform hover:scale-105 shadow-lg" >
                       Remove my account CJ
                    </button>
                </form>
                <br>
            </div>
        </div>
    </div>


    {{-- removes after 5 seconds --}}
    <script>
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => alert.remove());
    }, 5000); 

    //clean history
    
</script>
<script>
function connectCJ() {
    const width = 600;
    const height = 700;

    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);

    window.open(
        "cj/connect",
        "cjAuth",
        `width=${width},height=${height},top=${top},left=${left}`
    );
}

</script>
{{-- 
<script>
document.getElementById('connectCJBtn').addEventListener('click', function () {
    const width = 600;
    const height = 700;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;

    const popup = window.open(
        "{{ route('cj.redirect') }}", // your Laravel redirect route
        "cjAuth",
        `width=${width},height=${height},top=${top},left=${left}`
    );

    // Optional: check popup closed
    const timer = setInterval(function () {
        if (popup.closed) {
            clearInterval(timer);
            // Refresh the dashboard or show a message
            window.location.reload(); // or any JS update
        }
    }, 500);
});
</script> --}}




<script>
document.getElementById('connectCJBtn').addEventListener('click', function () {
    const width = 600;
    const height = 700;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;

    const popup = window.open(
        "{{ route('cj.redirect') }}",
        "cjAuth",
        `width=${width},height=${height},top=${top},left=${left}`
    );

    // Optional: listen for messages from popup
    window.addEventListener("message", function(event) {
        if (event.origin !== window.location.origin) return; // security
        if (event.data.status === "success") {
            alert("CJ Connected!");
            // optionally update dashboard via AJAX instead of reload
            window.location.reload();
        } else if (event.data.status === "cancelled") {
            alert("CJ Authorization Cancelled.");
        }
    });
});
</script>
</x-app-layout>
