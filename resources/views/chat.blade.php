@extends('layouts.app')

@section('content')
    <div id="messages" class="border p-2 h-64 overflow-y-scroll mb-4">
        @foreach($messages as $message)
            <p><strong>{{ $message->user ? $message->user->name : 'Unknown' }}:</strong> {{ $message->message }}</p>
        @endforeach
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

        window.Echo.private('chat')
            .listen('.message.sent', (e) => {

                let msgDiv = document.getElementById('messages');
                msgDiv.innerHTML += `<p><strong>${e.message.user.name}:</strong> ${e.message.message}</p>`;
                msgDiv.scrollTop = msgDiv.scrollHeight;

            });

    </script>
@endsection
