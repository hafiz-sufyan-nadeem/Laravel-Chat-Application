@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-6 bg-white shadow-lg rounded-lg border">

        {{-- Chat Header --}}
        <div class="px-4 py-3 border-b bg-gray-100 rounded-t-lg">
            <h2 class="text-lg font-semibold">Real-Time Chat</h2>
        </div>


    <form id="messageForm" class="flex">
        @csrf
        <input type="text" id="message" placeholder="Type a message..." class="flex-1 border p-2 mr-2" required>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2">Send</button>
    </form>

    <script>
        document.getElementById('messageForm').addEventListener('submit', function(e){
            e.preventDefault();

            fetch('/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: document.getElementById('message').value
                })
            });

            document.getElementById('message').value = '';
        });

        // AJAX polling system
        function loadMessages() {
            fetch('/messages')
                .then(res => res.json())
                .then(data => {
                    let box = document.getElementById('messages');
                    box.innerHTML = '';

                    data.forEach(msg => {
                        box.innerHTML += `<p><strong>${msg.user ? msg.user.name : 'Unknown'}:</strong> ${msg.message}</p>`;
                    });

                    box.scrollTop = box.scrollHeight;
                });
        }

        setInterval(loadMessages, 2000);
        loadMessages();
    </script>
@endsection
