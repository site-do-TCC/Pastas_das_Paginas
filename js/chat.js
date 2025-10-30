// ==================== MENU RESPONSIVO ====================
const menuBtn = document.getElementById("menu-btn");
const menu = document.getElementById("menu");

menuBtn.addEventListener("click", () => {
  menu.classList.toggle("show");
});

// ==================== CHAT SCRIPT ====================
document.addEventListener("DOMContentLoaded", () => {
  const chatListEl = document.getElementById("chat-list");
  const messagesEl = document.getElementById("chat-messages");
  const userNameEl = document.getElementById("chat-user-name");
  const userPhotoEl = document.getElementById("chat-user-photo");
  const userStatusEl = document.getElementById("chat-user-status");
  const searchInput = document.getElementById("search-input");
  const messageInput = document.getElementById("message-input");
  const sendBtn = document.getElementById("send-btn");

  let chats = [];
  let activeChatId = null;
  let chatHistories = {};

  // ---------- FUNÇÕES DE API (PLACEHOLDER) ----------
  const fetchChatList = async () => {
    // futuramente virá do backend
    return [
      { id: 1, name: "Chat", photo: "../img/SemFoto.jpg", lastMessage: "", online: true }
    ];
  };

  const fetchChatHistory = async (chatId) => {
    // futuramente virá do backend
    const sample = { 1: [{ id: "m1", from: "them", text: "Oi!" }] };
    return sample[chatId] || [];
  };

  const sendMessageToApi = async (chatId, text) => {
    // retorna mensagem local para exibição imediata
    return { id: "local-" + Date.now(), from: "me", text };
  };

  // ---------- RENDERIZAÇÃO ----------
  const renderChatList = (list) => {
    chatListEl.innerHTML = "";
    list.forEach(chat => {
      const item = document.createElement("div");
      item.classList.add("chat-item");
      if (chat.id === activeChatId) item.classList.add("active");
      item.dataset.chatId = chat.id;

      const avatar = document.createElement("div");
      avatar.className = "avatar";
      avatar.style.backgroundImage = `url('${chat.photo}')`;

      const meta = document.createElement("div");
      meta.className = "meta";
      meta.innerHTML = `
        <h4>${chat.name}</h4>
        <p>${chat.lastMessage || ""}</p>
      `;

      item.append(avatar, meta);
      item.addEventListener("click", () => openChat(chat.id));
      chatListEl.appendChild(item);
    });
  };

  const renderMessages = (messages) => {
    messagesEl.innerHTML = "";
    messages.forEach(m => {
      const div = document.createElement("div");
      div.classList.add("msg", m.from === "me" ? "outgoing" : "incoming");
      div.textContent = m.text;
      messagesEl.appendChild(div);
    });
    messagesEl.scrollTop = messagesEl.scrollHeight;
  };

  const setActiveChatUI = (chat) => {
    activeChatId = chat.id;
    userNameEl.textContent = chat.name;
    userPhotoEl.style.backgroundImage = `url('${chat.photo}')`;
    userStatusEl.textContent = chat.online ? "Online" : "Offline";
    document.querySelectorAll(".chat-item").forEach(el => el.classList.remove("active"));
    const activeEl = document.querySelector(`.chat-item[data-chat-id="${chat.id}"]`);
    if (activeEl) activeEl.classList.add("active");
  };

  // ---------- AÇÕES ----------
  const openChat = async (chatId) => {
    const chat = chats.find(c => c.id === chatId);
    if (!chat) return;
    setActiveChatUI(chat);
    if (!chatHistories[chatId]) {
      chatHistories[chatId] = await fetchChatHistory(chatId);
    }
    renderMessages(chatHistories[chatId]);
  };

  // Enviar mensagem
  sendBtn.addEventListener("click", async () => {
    const text = messageInput.value.trim();
    if (!text || !activeChatId) return;

    // exibir mensagem localmente
    const saved = await sendMessageToApi(activeChatId, text);
    chatHistories[activeChatId] = chatHistories[activeChatId] || [];
    chatHistories[activeChatId].push(saved);
    renderMessages(chatHistories[activeChatId]);
    messageInput.value = "";

    // atualizar preview da lista
    const idx = chats.findIndex(c => c.id === activeChatId);
    if (idx >= 0) {
      chats[idx].lastMessage = text;
      renderChatList(chats);
    }

    // ======= ENVIO PARA BACKEND =======
    const id_mensagem = Math.floor(Date.now() / 100); // temporario
    const id_chat = 1;        // chat ativo
    const id_remetente = 1;              // placeholder (usuário logado)
    const id_destinatario = 2;           // placeholder (destino)
    const conteudo = text;

    try {
      const response = await fetch('/Programacao_TCC_Avena/php/settings.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          id_mensagem,
          id_chat,
          id_remetente,
          id_destinatario,
          conteudo
        })
      });
      const result = await response.text();
      console.log("Retorno do PHP:", result);
    } catch (error) {
      console.error("Erro ao enviar mensagem:", error);
    }
  });

  messageInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      sendBtn.click();
    }
  });

  searchInput.addEventListener("input", (e) => {
    const q = e.target.value.toLowerCase();
    const filtered = chats.filter(c =>
      c.name.toLowerCase().includes(q) ||
      (c.lastMessage && c.lastMessage.toLowerCase().includes(q))
    );
    renderChatList(filtered);
  });

  // ---------- INICIALIZAÇÃO ----------
  (async function init() {
    chats = await fetchChatList();
    renderChatList(chats);
    if (chats.length > 0) await openChat(chats[0].id);
  })();

  // ---------- API GLOBAL (debug) ----------
  window.chatAPI = {
    fetchChatList,
    fetchChatHistory,
    sendMessageToApi,
    openChat,
    get state() { return { chats, activeChatId, chatHistories }; }
  };
});
