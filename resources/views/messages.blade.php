<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl mb-4">Notifications</h1>

        <div class="mb-4">
            <!-- method+action so native fallback targets the correct route if JS isn't loaded -->
            <form id="sendForm" method="POST" action="{{ route('messages.store') }}" class="flex gap-2" novalidate>
                @csrf
                <input id="messageInput" name="message" type="text" placeholder="Type a notification..."
                       class="flex-1 border rounded px-3 py-2" autocomplete="off" />
                <!-- button type="button" prevents native form submit — JS handles sending -->
                <button id="sendBtn" type="button" class="bg-blue-600 text-white px-4 py-2 rounded">Send</button>
            </form>
        </div>

        <hr class="mb-4" />

        <h2 class="text-xl mb-2">Incoming <span id="msgCount" class="text-sm text-gray-500">(0)</span></h2>
        <ul id="messageList" class="space-y-2"></ul>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
    (function () {
      console.log('Notifications inline script running');

      const list = document.getElementById('messageList');
      const form = document.getElementById('sendForm');
      const input = document.getElementById('messageInput');
      const sendBtn = document.getElementById('sendBtn');
      const countEl = document.getElementById('msgCount');

      if (!form || !input || !sendBtn || !list) {
        console.error('Notifications: required DOM elements missing', { form, input, sendBtn, list });
        return;
      }

      let count = 0;
      function updateCount() { if (countEl) countEl.textContent = `(${count})`; }

      function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"'`=\/]/g, s => ({
          '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;', '/':'&#x2F;', '`':'&#x60;', '=':'&#x3D;'
        })[s]);
      }

      function formatTimestamp(iso) {
        try { return new Date(iso).toLocaleString(); } catch (e) { return iso ?? ''; }
      }

      function appendMessage(data) {
        const li = document.createElement('li');
        li.className = 'p-3 bg-gray-100 rounded';
        const user = escapeHtml(data.sender_id ?? (data.sender?.id ?? 'Unknown'));
        const body = escapeHtml(data.body ?? data.message ?? JSON.stringify(data));
        const created = formatTimestamp(data.created_at ?? data.createdAt ?? new Date().toISOString());

        li.innerHTML = `
          <div><strong>User ${user}</strong></div>
          <div class="mt-1">${body}</div>
          <div class="text-sm text-gray-600 mt-1">At ${created}</div>
        `;
        list.prepend(li);
        count++;
        updateCount();
      }

      // Prevent any native form submission (double-safety)
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
      }, { capture: true });

      // Core send function
      async function doSend(text) {
        const message = String(text ?? input.value ?? '').trim();
        if (!message) {
          console.warn('Notifications: message empty — nothing to send');
          return { ok: false, error: 'empty' };
        }

        sendBtn.disabled = true;
        const prevText = sendBtn.textContent;
        sendBtn.textContent = 'Sending...';

        try {
          const res = await fetch(form.action, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message })
          });

          if (!res.ok) {
            const body = await res.text().catch(()=>null);
            console.error('Notifications: send failed', res.status, body);
            return { ok: false, status: res.status, body };
          }

          input.value = '';
          console.log('Notifications: message sent');
          return { ok: true };
        } catch (err) {
          console.error('Notifications: network error', err);
          return { ok: false, error: 'network', detail: err };
        } finally {
          sendBtn.disabled = false;
          sendBtn.textContent = prevText;
        }
      }

      // Expose safe globals (for debugging and legacy callers)
      window.__sendMessage = function(payload) {
        return doSend(payload && payload.text ? payload.text : undefined);
      };
      window.sendMessage = function(e) {
        if (e && e.preventDefault) e.preventDefault();
        doSend();
        return false;
      };

      // Hook UI
      sendBtn.addEventListener('click', function (e) {
        e.preventDefault();
        doSend();
      });

      input.addEventListener('keydown', function (ev) {
        if (ev.key === 'Enter') {
          ev.preventDefault();
          doSend();
        }
      });

      console.log('Notifications script attached — sendBtn listener added');

      // Echo / realtime registration (safe: no error if Echo is not present)
      function registerEcho() {
        if (!window.Echo) {
          console.warn('Echo not found; realtime disabled for now.');
          return;
        }

        try {
          window.Echo.channel('messages')
            .listen('.message.received', (e) => appendMessage(e))
            .listen('MessageReceived', (e) => appendMessage(e));
          console.log('Echo listeners registered for messages channel');
        } catch (err) {
          console.warn('Failed to subscribe to messages channel:', err);
        }
      }

      // Try to register now and shortly after (in case Echo is initialized later)
      // Echo may not be ready yet — wait for echo-ready event from echo.js
if (window.Echo) {
    registerEcho();
} else {
    window.addEventListener("echo-ready", () => {
        console.log("echo-ready received — Echo is now available");
        registerEcho();
    }, { once: true });

    setTimeout(() => {
        if (!window.Echo) {
            console.warn("Echo still missing after 2 seconds — echo.js may not have executed.");
        }
    }, 2000);
}

    })();
    </script>

</x-app-layout>
