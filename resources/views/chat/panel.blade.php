@php
    $mode = $mode ?? 'floating';
    $startVendorId = $startVendorId ?? null;
@endphp

<div
    id="rnChatShell"
    class="rn-chat-shell {{ $mode === 'page' ? 'rn-chat-shell-page' : 'rn-chat-shell-floating' }}"
    data-mode="{{ $mode }}"
    data-start-vendor-id="{{ $startVendorId ?: '' }}"
    data-url-conversations="{{ route('chat.conversations') }}"
    data-url-unread="{{ route('chat.unread') }}"
    data-url-start-template="{{ route('chat.start.vendor', ['vendor' => '__VENDOR__']) }}"
    data-url-messages-template="{{ route('chat.messages', ['conversation' => '__CONV__']) }}"
    data-url-send-template="{{ route('chat.send', ['conversation' => '__CONV__']) }}"
    data-url-read-template="{{ route('chat.read', ['conversation' => '__CONV__']) }}"
>
    @if($mode !== 'page')
        <button type="button" id="rnChatFab" class="rn-chat-fab" aria-label="Buka chat">
            <i class="fa-solid fa-comments"></i>
            <span>Chat</span>
            <span id="rnChatFabBadge" class="rn-chat-fab-badge hidden">0</span>
        </button>
    @endif

    <div id="rnChatPanel" class="rn-chat-panel {{ $mode === 'page' ? 'open' : '' }}">
        <div class="rn-chat-left">
            <div class="rn-chat-left-head">
                <h3>Chat</h3>
                <span id="rnChatLeftUnread" class="rn-chat-total-unread hidden">0</span>
            </div>
            <div class="rn-chat-search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="rnChatSearch" placeholder="Cari nama vendor / user">
            </div>
            <div id="rnChatConversationList" class="rn-chat-conversation-list"></div>
        </div>

        <div class="rn-chat-right">
            <div id="rnChatHeader" class="rn-chat-header hidden">
                <div>
                    <p id="rnChatHeaderName" class="rn-chat-header-name">-</p>
                    <p id="rnChatHeaderSubtitle" class="rn-chat-header-subtitle">-</p>
                </div>
                <div class="rn-chat-header-actions">
                    <button type="button" id="rnChatBackToList" class="rn-chat-icon-btn rn-chat-mobile-only" title="Kembali ke list chat">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    @if($mode !== 'page')
                        <button type="button" id="rnChatToggleRoom" class="rn-chat-icon-btn rn-chat-hide-room-btn" title="Sembunyikan jendela obrolan">
                            <i class="fa-solid fa-window-minimize"></i>
                        </button>
                        <button type="button" id="rnChatClose" class="rn-chat-icon-btn" title="Tutup chat">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    @endif
                </div>
            </div>

            <div id="rnChatWelcome" class="rn-chat-welcome">
                <div class="rn-chat-welcome-icon"><i class="fa-regular fa-comments"></i></div>
                <h4>Selamat Datang di Chat RENMOTE</h4>
                <p>Pilih room chat untuk mulai komunikasi dengan vendor atau pelanggan.</p>
            </div>

            <div id="rnChatMessages" class="rn-chat-messages hidden"></div>

            <form id="rnChatForm" class="rn-chat-form hidden">
                <textarea id="rnChatInput" rows="1" placeholder="Tulis pesan..."></textarea>
                <button type="submit" id="rnChatSendBtn">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            (() => {
                const shell = document.getElementById('rnChatShell');
                if (!shell) {
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const mode = shell.dataset.mode || 'floating';
                const isFloating = mode !== 'page';
                const startVendorId = shell.dataset.startVendorId ? Number(shell.dataset.startVendorId) : null;

                const urls = {
                    conversations: shell.dataset.urlConversations,
                    unread: shell.dataset.urlUnread,
                    startTemplate: shell.dataset.urlStartTemplate,
                    messagesTemplate: shell.dataset.urlMessagesTemplate,
                    sendTemplate: shell.dataset.urlSendTemplate,
                    readTemplate: shell.dataset.urlReadTemplate,
                };

                const elements = {
                    fab: document.getElementById('rnChatFab'),
                    fabBadge: document.getElementById('rnChatFabBadge'),
                    panel: document.getElementById('rnChatPanel'),
                    close: document.getElementById('rnChatClose'),
                    toggleRoom: document.getElementById('rnChatToggleRoom'),
                    backToList: document.getElementById('rnChatBackToList'),
                    list: document.getElementById('rnChatConversationList'),
                    search: document.getElementById('rnChatSearch'),
                    header: document.getElementById('rnChatHeader'),
                    headerName: document.getElementById('rnChatHeaderName'),
                    headerSubtitle: document.getElementById('rnChatHeaderSubtitle'),
                    welcome: document.getElementById('rnChatWelcome'),
                    messages: document.getElementById('rnChatMessages'),
                    form: document.getElementById('rnChatForm'),
                    input: document.getElementById('rnChatInput'),
                    sendBtn: document.getElementById('rnChatSendBtn'),
                    leftUnread: document.getElementById('rnChatLeftUnread'),
                };

                const state = {
                    activeConversationId: null,
                    conversations: [],
                    unreadCount: 0,
                    lastMessageId: 0,
                    searchTimer: null,
                    pollTimer: null,
                    isOpen: mode === 'page',
                    isDraftConversation: false,
                    roomHidden: false,
                };

                const isMobileViewport = () => window.matchMedia('(max-width: 1024px)').matches;

                const escapeHtml = (raw) => {
                    const div = document.createElement('div');
                    div.textContent = raw ?? '';
                    return div.innerHTML;
                };

                const templateUrl = (template, token, value) => template.replace(token, encodeURIComponent(String(value)));

                const setMobileThreadMode = (isThread) => {
                    if (!elements.panel) {
                        return;
                    }

                    if (isMobileViewport()) {
                        elements.panel.classList.toggle('rn-chat-mobile-thread', Boolean(isThread));
                    } else {
                        elements.panel.classList.remove('rn-chat-mobile-thread');
                    }
                };

                const updateToggleRoomButton = () => {
                    if (!elements.toggleRoom) {
                        return;
                    }

                    const icon = elements.toggleRoom.querySelector('i');

                    if (state.roomHidden) {
                        elements.toggleRoom.title = 'Tampilkan jendela obrolan';
                        if (icon) {
                            icon.className = 'fa-solid fa-window-restore';
                        }
                    } else {
                        elements.toggleRoom.title = 'Sembunyikan jendela obrolan';
                        if (icon) {
                            icon.className = 'fa-solid fa-window-minimize';
                        }
                    }
                };

                const setRoomHidden = (hidden) => {
                    if (!isFloating || !elements.panel) {
                        return;
                    }

                    if (isMobileViewport()) {
                        state.roomHidden = false;
                        elements.panel.classList.remove('rn-chat-room-hidden');
                        updateToggleRoomButton();
                        return;
                    }

                    state.roomHidden = Boolean(hidden);
                    elements.panel.classList.toggle('rn-chat-room-hidden', state.roomHidden);
                    updateToggleRoomButton();
                };

                const fetchJson = async (url, options = {}) => {
                    const requestOptions = {
                        method: options.method || 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    };

                    if (options.body !== undefined) {
                        requestOptions.headers['Content-Type'] = 'application/json';
                        requestOptions.body = JSON.stringify(options.body);
                    }

                    const response = await fetch(url, requestOptions);
                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw new Error(data.message || 'Request chat gagal diproses.');
                    }

                    return data;
                };

                const setUnreadCount = (count) => {
                    state.unreadCount = Number(count || 0);
                    const shouldShow = state.unreadCount > 0;

                    [elements.fabBadge, elements.leftUnread].forEach((el) => {
                        if (!el) {
                            return;
                        }

                        el.textContent = String(state.unreadCount);
                        el.classList.toggle('hidden', !shouldShow);
                        el.hidden = !shouldShow;
                    });
                };

                const renderConversations = () => {
                    if (!elements.list) {
                        return;
                    }

                    if (!state.conversations.length) {
                        elements.list.innerHTML = '<div class="rn-chat-empty">Belum ada room chat. Mulai chat dari kartu vendor.</div>';
                        return;
                    }

                    elements.list.innerHTML = state.conversations.map((conversation) => {
                        const activeClass = Number(conversation.id) === Number(state.activeConversationId) ? 'active' : '';
                        const unreadBadge = conversation.unread_count > 0
                            ? `<span class="rn-chat-conversation-badge">${conversation.unread_count}</span>`
                            : '';
                        const avatar = conversation.counterpart_photo_url
                            ? `<span class="rn-chat-avatar"><img class="rn-chat-avatar-img" src="${escapeHtml(conversation.counterpart_photo_url)}" alt="${escapeHtml(conversation.counterpart_name)}" onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"><span class="rn-chat-avatar-fallback hidden">${escapeHtml(conversation.counterpart_avatar || 'RN')}</span></span>`
                            : `<span class="rn-chat-avatar">${escapeHtml(conversation.counterpart_avatar || 'RN')}</span>`;

                        return `
                            <button type="button" class="rn-chat-conversation-item ${activeClass}" data-conversation-id="${conversation.id}">
                                ${avatar}
                                <span class="rn-chat-conversation-body">
                                    <span class="rn-chat-conversation-top">
                                        <span class="rn-chat-conversation-name">${escapeHtml(conversation.counterpart_name)}</span>
                                        <span class="rn-chat-conversation-time">${escapeHtml(conversation.last_message_label || '-')}</span>
                                    </span>
                                    <span class="rn-chat-conversation-subtitle">${escapeHtml(conversation.counterpart_subtitle || '-')}</span>
                                    <span class="rn-chat-conversation-preview">${escapeHtml(conversation.last_message_preview || '-')}</span>
                                    ${unreadBadge}
                                </span>
                            </button>
                        `;
                    }).join('');
                };

                const renderMessages = (messages, append = false) => {
                    if (!elements.messages) {
                        return;
                    }

                    if (!append) {
                        elements.messages.innerHTML = '';
                    }

                    const oldHeight = elements.messages.scrollHeight;

                    messages.forEach((message) => {
                        const row = document.createElement('div');
                        row.className = `rn-chat-message-row ${message.is_mine ? 'mine' : ''}`;
                        row.dataset.messageId = String(message.id);
                        row.innerHTML = `
                            <div class="rn-chat-bubble">
                                <p class="rn-chat-bubble-text">${escapeHtml(message.body)}</p>
                                <p class="rn-chat-bubble-meta">${escapeHtml(message.created_label || '')}</p>
                            </div>
                        `;
                        elements.messages.appendChild(row);
                        state.lastMessageId = Math.max(state.lastMessageId, Number(message.id));
                    });

                    if (!append) {
                        elements.messages.scrollTop = elements.messages.scrollHeight;
                    } else {
                        const changed = elements.messages.scrollHeight - oldHeight;
                        const nearBottom = (elements.messages.scrollHeight - elements.messages.scrollTop - elements.messages.clientHeight) < 120;

                        if (nearBottom || changed > 0) {
                            elements.messages.scrollTop = elements.messages.scrollHeight;
                        }
                    }
                };

                const showConversationUI = (conversation) => {
                    if (!conversation) {
                        return;
                    }

                    elements.headerName.textContent = conversation.counterpart_name || '-';
                    elements.headerSubtitle.textContent = conversation.counterpart_subtitle || '-';
                    elements.header.classList.remove('hidden');
                    elements.welcome.classList.add('hidden');
                    elements.messages.classList.remove('hidden');
                    elements.form.classList.remove('hidden');

                    setMobileThreadMode(true);
                };

                const hideConversationUI = () => {
                    elements.header.classList.add('hidden');
                    elements.welcome.classList.remove('hidden');
                    elements.messages.classList.add('hidden');
                    elements.form.classList.add('hidden');
                    elements.messages.innerHTML = '';
                    state.lastMessageId = 0;
                    setMobileThreadMode(false);
                };

                const loadUnread = async () => {
                    try {
                        const data = await fetchJson(urls.unread);
                        setUnreadCount(data.unread_count || 0);
                    } catch (error) {
                        // Silent for background polling.
                    }
                };

                const loadConversations = async ({ preserveSelection = true, keyword = '' } = {}) => {
                    const params = new URLSearchParams();
                    if (keyword) {
                        params.set('q', keyword);
                    }

                    const url = params.toString() ? `${urls.conversations}?${params.toString()}` : urls.conversations;
                    const data = await fetchJson(url);
                    state.conversations = Array.isArray(data.conversations) ? data.conversations : [];

                    setUnreadCount(data.unread_count || 0);
                    renderConversations();

                    if (!preserveSelection && state.conversations.length > 0) {
                        state.activeConversationId = state.conversations[0].id;
                    }

                    if (state.activeConversationId) {
                        const exists = state.conversations.some((item) => Number(item.id) === Number(state.activeConversationId));
                        if (!exists && !state.isDraftConversation) {
                            state.activeConversationId = null;
                            hideConversationUI();
                        }
                    }
                };

                const markConversationRead = async (conversationId) => {
                    if (!conversationId) {
                        return;
                    }

                    const url = templateUrl(urls.readTemplate, '__CONV__', conversationId);
                    try {
                        const data = await fetchJson(url, { method: 'POST' });
                        setUnreadCount(data.unread_count || 0);
                    } catch (error) {
                        // Ignore read errors to keep UX smooth.
                    }
                };

                const loadMessages = async (conversationId, { append = false } = {}) => {
                    const query = append && state.lastMessageId > 0 ? `?after_id=${state.lastMessageId}` : '';
                    const url = `${templateUrl(urls.messagesTemplate, '__CONV__', conversationId)}${query}`;
                    const data = await fetchJson(url);

                    const currentConversation = state.conversations.find((item) => Number(item.id) === Number(conversationId)) || data.conversation;
                    showConversationUI(currentConversation);

                    const messages = Array.isArray(data.messages) ? data.messages : [];

                    if (append) {
                        renderMessages(messages, true);
                    } else {
                        state.lastMessageId = 0;
                        renderMessages(messages, false);
                    }

                    if (messages.length > 0) {
                        state.isDraftConversation = false;
                    }

                    await markConversationRead(conversationId);

                    state.conversations = state.conversations.map((item) => {
                        if (Number(item.id) === Number(conversationId)) {
                            return {
                                ...item,
                                unread_count: 0,
                            };
                        }
                        return item;
                    });
                    renderConversations();
                };

                const selectConversation = async (conversationId, options = {}) => {
                    const isDraft = options.isDraft === true;

                    state.activeConversationId = Number(conversationId);
                    state.isDraftConversation = isDraft;
                    setRoomHidden(false);
                    renderConversations();
                    await loadMessages(state.activeConversationId, { append: false });
                };

                const sendMessage = async () => {
                    if (!state.activeConversationId) {
                        return;
                    }

                    const body = (elements.input.value || '').trim();
                    if (!body) {
                        return;
                    }

                    const url = templateUrl(urls.sendTemplate, '__CONV__', state.activeConversationId);
                    elements.sendBtn.disabled = true;

                    try {
                        const data = await fetchJson(url, {
                            method: 'POST',
                            body: { body },
                        });

                        elements.input.value = '';
                        elements.input.style.height = '44px';

                        if (data.message) {
                            renderMessages([data.message], true);
                        }

                        state.isDraftConversation = false;

                        await loadConversations({ preserveSelection: true, keyword: elements.search.value.trim() });
                        setUnreadCount(data.unread_count || state.unreadCount);
                    } catch (error) {
                        alert(error.message || 'Pesan gagal dikirim.');
                    } finally {
                        elements.sendBtn.disabled = false;
                        elements.input.focus();
                    }
                };

                const openPanel = async () => {
                    if (!elements.panel) {
                        return;
                    }

                    state.isOpen = true;
                    elements.panel.classList.add('open');
                    updateToggleRoomButton();
                    setMobileThreadMode(false);

                    await loadConversations({ preserveSelection: true, keyword: elements.search.value.trim() });
                    await loadUnread();

                    if (state.activeConversationId) {
                        await loadMessages(state.activeConversationId, { append: false });
                    }
                };

                const closePanel = () => {
                    if (!isFloating) {
                        return;
                    }

                    state.isOpen = false;
                    elements.panel.classList.remove('open');
                    elements.panel.classList.remove('rn-chat-room-hidden');
                    elements.panel.classList.remove('rn-chat-mobile-thread');
                };

                const startConversationWithVendor = async (vendorId) => {
                    if (!vendorId) {
                        return;
                    }

                    const url = templateUrl(urls.startTemplate, '__VENDOR__', vendorId);

                    try {
                        const data = await fetchJson(url, { method: 'POST' });
                        await openPanel();
                        state.activeConversationId = Number(data.conversation_id);
                        await selectConversation(state.activeConversationId, { isDraft: !data.has_messages });
                    } catch (error) {
                        alert(error.message || 'Gagal memulai percakapan dengan vendor.');
                    }
                };

                const setupListeners = () => {
                    if (elements.fab) {
                        elements.fab.addEventListener('click', () => {
                            if (state.isOpen) {
                                closePanel();
                            } else {
                                openPanel();
                            }
                        });
                    }

                    if (elements.close) {
                        elements.close.addEventListener('click', closePanel);
                    }

                    if (elements.toggleRoom) {
                        elements.toggleRoom.addEventListener('click', () => {
                            setRoomHidden(!state.roomHidden);
                        });
                    }

                    if (elements.backToList) {
                        elements.backToList.addEventListener('click', () => {
                            setMobileThreadMode(false);
                        });
                    }

                    document.addEventListener('click', (event) => {
                        const chatTrigger = event.target.closest('[data-chat-vendor-id]');
                        if (chatTrigger) {
                            event.preventDefault();
                            startConversationWithVendor(Number(chatTrigger.dataset.chatVendorId));
                        }
                    });

                    if (elements.list) {
                        elements.list.addEventListener('click', (event) => {
                            const target = event.target.closest('[data-conversation-id]');
                            if (!target) {
                                return;
                            }

                            selectConversation(Number(target.dataset.conversationId), { isDraft: false });
                        });
                    }

                    if (elements.search) {
                        elements.search.addEventListener('input', () => {
                            clearTimeout(state.searchTimer);
                            state.searchTimer = setTimeout(() => {
                                loadConversations({ preserveSelection: true, keyword: elements.search.value.trim() });
                            }, 250);
                        });
                    }

                    if (elements.input) {
                        elements.input.addEventListener('input', () => {
                            elements.input.style.height = '44px';
                            elements.input.style.height = `${Math.min(elements.input.scrollHeight, 120)}px`;
                        });

                        elements.input.addEventListener('keydown', (event) => {
                            if (event.key === 'Enter' && !event.shiftKey) {
                                event.preventDefault();
                                sendMessage();
                            }
                        });
                    }

                    if (elements.form) {
                        elements.form.addEventListener('submit', (event) => {
                            event.preventDefault();
                            sendMessage();
                        });
                    }
                };

                const setupPolling = () => {
                    clearInterval(state.pollTimer);
                    state.pollTimer = setInterval(async () => {
                        if (isFloating && !state.isOpen) {
                            await loadUnread();
                            return;
                        }

                        await loadConversations({ preserveSelection: true, keyword: elements.search.value.trim() });

                        if (state.activeConversationId) {
                            await loadMessages(state.activeConversationId, { append: true });
                        }
                    }, 3000);
                };

                const boot = async () => {
                    setupListeners();
                    setupPolling();
                    await loadUnread();

                    if (mode === 'page') {
                        await openPanel();
                        if (!state.activeConversationId && state.conversations.length > 0) {
                            await selectConversation(state.conversations[0].id, { isDraft: false });
                        }
                    }

                    if (startVendorId) {
                        await startConversationWithVendor(startVendorId);
                    }

                    window.addEventListener('resize', () => {
                        if (!isMobileViewport()) {
                            elements.panel.classList.remove('rn-chat-mobile-thread');
                        }
                    });
                };

                boot();
            })();
        </script>
    @endpush
@endonce
