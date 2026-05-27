            </main>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════
         CHATBOT
    ═══════════════════════════════════════════ -->
    <div id="chatbot-fab" onclick="toggleChat()" title="AI Assistant">
        <i class="fas fa-robot" id="chatFabIcon"></i>
        <span class="chat-fab-badge" id="chatBadge" style="display:none">1</span>
    </div>

    <div id="chatbot-window" class="chatbot-window">
        <div class="chatbot-header">
            <div class="chatbot-header-info">
                <div class="chatbot-avatar"><i class="fas fa-hard-hat"></i></div>
                <div>
                    <div class="chatbot-title">ConstructBot</div>
                    <div class="chatbot-status"><span class="status-dot"></span> AI Assistant</div>
                </div>
            </div>
            <button class="chatbot-close" onclick="toggleChat()"><i class="fas fa-times"></i></button>
        </div>

        <div class="chatbot-messages" id="chatMessages">
            <div class="chat-msg bot">
                <div class="chat-bubble">
                    👋 Hello! I'm <strong>ConstructBot</strong>, your AI assistant for this construction management system.<br><br>
                    I can help you with managing <strong>projects, clients, situations, and documents</strong>. What do you need?
                </div>
            </div>
        </div>

        <div class="chatbot-input-area">
            <textarea id="chatInput" class="chatbot-input" placeholder="Ask me anything…" rows="1"
                onkeydown="handleChatKey(event)" oninput="autoResize(this)"></textarea>
            <button class="chatbot-send" id="chatSendBtn" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script>
    // ════════════════════════════════════════════
    // SIDEBAR TOGGLE (Claude-style)
    // ════════════════════════════════════════════
    (function() {
        const sidebar  = document.getElementById('sidebar');
        const main     = document.getElementById('appMain');
        const overlay  = document.getElementById('sidebarOverlay');
        const MOBILE   = () => window.innerWidth <= 768;
        const KEY      = 'sidebar_collapsed';

        // Desktop: restore saved state
        if (!MOBILE() && localStorage.getItem(KEY) === '1') {
            sidebar.classList.add('collapsed');
            main   && main.classList.add('sidebar-collapsed');
        }

        window.toggleSidebar = function() {
            if (MOBILE()) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                main   && main.classList.toggle('sidebar-collapsed');
                localStorage.setItem(KEY, sidebar.classList.contains('collapsed') ? '1' : '0');
            }
        };

        window.closeSidebar = function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        };

        // Close on resize from mobile → desktop
        window.addEventListener('resize', function() {
            if (!MOBILE()) {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            }
        });
    })();

    // ════════════════════════════════════════════
    // CHATBOT
    // ════════════════════════════════════════════
    let chatOpen      = false;
    let chatHistory   = [];
    let badgeShown    = false;

    // Show badge after 3s if chat was never opened
    setTimeout(function() {
        if (!chatOpen && !badgeShown) {
            document.getElementById('chatBadge').style.display = 'flex';
            badgeShown = true;
        }
    }, 3000);

    function toggleChat() {
        chatOpen = !chatOpen;
        const win  = document.getElementById('chatbot-window');
        const icon = document.getElementById('chatFabIcon');
        const badge = document.getElementById('chatBadge');
        win.classList.toggle('open', chatOpen);
        icon.className = chatOpen ? 'fas fa-times' : 'fas fa-robot';
        if (chatOpen) {
            badge.style.display = 'none';
            badgeShown = true;
            setTimeout(() => document.getElementById('chatInput').focus(), 300);
        }
    }

    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    }

    function handleChatKey(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    }

    function appendMsg(role, html) {
        const msgs = document.getElementById('chatMessages');
        const div  = document.createElement('div');
        div.className = 'chat-msg ' + role;
        div.innerHTML = '<div class="chat-bubble">' + html + '</div>';
        msgs.appendChild(div);
        msgs.scrollTop = msgs.scrollHeight;
        return div;
    }

    function showTyping() {
        const msgs = document.getElementById('chatMessages');
        const div  = document.createElement('div');
        div.className = 'chat-msg bot';
        div.id = 'typingIndicator';
        div.innerHTML = '<div class="chat-bubble typing"><span></span><span></span><span></span></div>';
        msgs.appendChild(div);
        msgs.scrollTop = msgs.scrollHeight;
    }

    function removeTyping() {
        const el = document.getElementById('typingIndicator');
        if (el) el.remove();
    }

    function escapeHtml(t) {
        return t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                .replace(/\n/g,'<br>').replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>');
    }

    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const text  = input.value.trim();
        if (!text) return;

        input.value = '';
        input.style.height = 'auto';
        document.getElementById('chatSendBtn').disabled = true;

        appendMsg('user', escapeHtml(text));
        chatHistory.push({ role: 'user', content: text });

        showTyping();

        try {
            const res = await fetch('index.php?controller=chat&action=message', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    system: `You are ConstructBot, the AI assistant embedded inside a PHP construction management web application called ConstructManager. 
The app manages: Clients, Projects (with budgets and dates), Situations (progress snapshots per project), Works (tasks with quantities and unit prices), and Documents.
Keep your answers concise, practical, and focused on construction management. Use simple markdown like **bold** for emphasis. Avoid lengthy preambles.`,
                    messages: chatHistory,
                    max_tokens: 1000
                })
            });

            const data = await res.json();
            removeTyping();

            const reply = (data && typeof data.reply === 'string' && data.reply.trim() !== '')
                ? data.reply
                : (data && data.error)
                    ? `⚠️ ${data.error}${data.hint ? `\n\n${data.hint}` : ''}`
                    : (!res.ok)
                        ? '⚠️ Server error while contacting AI. Check server logs / API key.'
                        : '⚠️ Sorry, I could not get a response. Please try again.';

            chatHistory.push({ role: 'assistant', content: reply });
            appendMsg('bot', escapeHtml(reply));

        } catch (err) {
            removeTyping();
            appendMsg('bot', '⚠️ Connection error. Make sure you have internet access.');
        }

        document.getElementById('chatSendBtn').disabled = false;
        document.getElementById('chatInput').focus();
    }
    </script>
    <script src="<?php echo $base_url; ?>/assets/js/main.js"></script>
</body>
</html>
