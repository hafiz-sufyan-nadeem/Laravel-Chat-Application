@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-6 bg-white shadow-lg rounded-lg border">

        {{-- Chat Header --}}
        <div class="px-4 py-3 border-b bg-gray-100 rounded-t-lg">
            <h2 class="text-lg font-semibold">Real-Time Chat</h2>
        </div>

        {{-- Messages Box --}}
        <div id="messages" class="p-4 h-96 overflow-y-scroll space-y-3 bg-gray-50">
            @foreach($messages as $message)
                <div class="flex flex-col">
                <span class="font-semibold text-blue-700">
                    {{ $message->user ? $message->user->name : 'Unknown' }}
                </span>

                    <span class="inline-block bg-white p-3 rounded-xl shadow-sm border text-gray-800">
                    {{ $message->message }}
                </span>
                </div>
            @endforeach
        </div>


        {{-- Message Input Box --}}
        <div class="border-t p-3 bg-white rounded-b-lg">
            <form id="messageForm" class="flex gap-2">
                @csrf
                <input
                    type="text"
                    id="message"
                    placeholder="Type your message..."
                    class="flex-1 border rounded-full px-4 py-2 focus:outline-none focus:border-blue-500"
                    required
                >
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-all"
                >
                    Send
                </button>
            </form>
        </div>
    </div>


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
