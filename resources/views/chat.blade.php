<!DOCTYPE html>
<html>
<head>
    <title>Chat App</title>
</head>
<body>

<h2>Laravel Real-Time Chat</h2>

<div id="messages">
    @foreach($messages as $msg)
        <p><strong>{{ $msg->user->name }}:</strong> {{ $msg->message }}</p>
    @endforeach
</div>

<form id="messageForm">
    @csrf
    <input type="text" name="message" id="message" placeholder="Type message..." required>
    <button type="submit">Send</button>
</form>

<script src="/js/app.js"></script>

<script>
    // send message
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

    // listen to new real-time messages
    window.Echo.join('chat')
        .listen('.message-sent', (e) => {
            let msgDiv = document.getElementById('messages');
            msgDiv.innerHTML += `<p><strong>${e.message.user.name}:</strong> ${e.message.message}</p>`;
        });
</script>

</body>
</html>
