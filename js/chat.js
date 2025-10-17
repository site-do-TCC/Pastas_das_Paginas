// Pegar o botão e o menu
const menuBtn = document.getElementById("menu-btn");
const menu = document.getElementById("menu");

// Quando clicar no botão, alternar classe
menuBtn.addEventListener("click", () => {
  menu.classList.toggle("show");
});
/* chat.js
   Arquivo de integração: contém placeholders e funções para popular a lista de chats
   e as mensagens. Troque as funções fetch* pelos seus endpoints quando tiver a API.
*/

document.addEventListener("DOMContentLoaded", () => {
  // elementos
  const chatListEl = document.getElementById("chat-list");
  const messagesEl = document.getElementById("chat-messages");
  const userNameEl = document.getElementById("chat-user-name");
  const userPhotoEl = document.getElementById("chat-user-photo");
  const userStatusEl = document.getElementById("chat-user-status");
  const searchInput = document.getElementById("search-input");
  const messageInput = document.getElementById("message-input");
  const sendBtn = document.getElementById("send-btn");

  // estado local (será substituído por dados reais)
  let chats = [];
  let activeChatId = null;
  let chatHistories = {}; // { chatId: [messages...] }

  // ---------- FUNÇÕES DE API (placeholder) ----------
  // Substitua estas funções por chamadas reais ao seu backend (fetch / axios / websocket)

  // Pegar lista de chats (history)
  function fetchChatList() {
    // TODO: substituir por fetch('/api/chats') etc.
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve([
          { id: "c1", name: "Chat", photo: "../img/SemFoto.jpg", lastMessage: "..", online: true },

        ]);
      }, 200);
    });
  }

  // Pegar histórico de mensagens de um chat
  function fetchChatHistory(chatId) {
    // TODO: substituir por fetch(`/api/chats/${chatId}/messages`)
    return new Promise((resolve) => {
      setTimeout(() => {
        const sample = {
          c1: [
            { id: "m1", from: "them", text: "Exemplo" },
            { id: "m2", from: "them", text: "OIII" },
          ],

        };
        resolve(sample[chatId] || []);
      }, 200);
    });
  }

  // Enviar mensagem (placeholder)
  function sendMessageToApi(chatId, text) {
    // TODO: substituir por POST para armazenar a mensagem no backend
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({ id: "local-" + Date.now(), from: "me", text });
      }, 150);
    });
  }

  // ---------- RENDERIZAÇÃO ----------
  function renderChatList(list) {
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

      const h4 = document.createElement("h4");
      h4.textContent = chat.name;

      const p = document.createElement("p");
      p.textContent = chat.lastMessage || "";

      meta.appendChild(h4);
      meta.appendChild(p);

      item.appendChild(avatar);
      item.appendChild(meta);

      item.addEventListener("click", () => {
        openChat(chat.id);
      });

      chatListEl.appendChild(item);
    });
  }

  function renderMessages(messages) {
    messagesEl.innerHTML = "";
    messages.forEach(m => {
      const div = document.createElement("div");
      div.classList.add("msg");
      div.classList.add(m.from === "me" ? "outgoing" : "incoming");
      div.textContent = m.text;
      messagesEl.appendChild(div);
    });
    // scroll para o final
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  function setActiveChatUI(chat) {
    activeChatId = chat.id;
    userNameEl.textContent = chat.name;
    userPhotoEl.style.backgroundImage = `url('${chat.photo}')`;
    userStatusEl.textContent = chat.online ? "Online" : "Último visto";
    // marca como ativo na lista
    document.querySelectorAll(".chat-item").forEach(el => el.classList.remove("active"));
    const activeEl = document.querySelector(`.chat-item[data-chat-id="${chat.id}"]`);
    if (activeEl) activeEl.classList.add("active");
  }

  // ---------- AÇÕES ----------
  async function openChat(chatId) {
    const chat = chats.find(c => c.id === chatId);
    if (!chat) return;
    setActiveChatUI(chat);
    // carregar histórico (ou pegar do cache)
    if (!chatHistories[chatId]) {
      chatHistories[chatId] = await fetchChatHistory(chatId);
    }
    renderMessages(chatHistories[chatId]);
  }

  // enviar mensagem
  sendBtn.addEventListener("click", async () => {
    const text = messageInput.value.trim();
    if (!text || !activeChatId) return;
    // criar mensagem localmente e enviar para API
    const saved = await sendMessageToApi(activeChatId, text);
    // atualizar histórico local
    chatHistories[activeChatId] = chatHistories[activeChatId] || [];
    chatHistories[activeChatId].push(saved);
    renderMessages(chatHistories[activeChatId]);
    messageInput.value = "";
    // Atualize a lista de chats (última mensagem) localmente
    const idx = chats.findIndex(c => c.id === activeChatId);
    if (idx >= 0) {
      chats[idx].lastMessage = text;
      renderChatList(chats);
    }
  });

  // enviar com Enter
  messageInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      sendBtn.click();
    }
  });

  // Busca simples na lista de chats
  searchInput.addEventListener("input", (e) => {
    const q = e.target.value.toLowerCase();
    const filtered = chats.filter(c => c.name.toLowerCase().includes(q) || (c.lastMessage && c.lastMessage.toLowerCase().includes(q)));
    renderChatList(filtered);
  });

  // ---------- INICIALIZAÇÃO ----------
  (async function init() {
    chats = await fetchChatList();
    renderChatList(chats);

    // Abrir o primeiro chat por padrão (único chat aberto)
    if (chats.length > 0) {
      await openChat(chats[0].id);
    }
  })();

  // Expor funções úteis no window para facilitar debug/integracao
  window.chatAPI = {
    fetchChatList,
    fetchChatHistory,
    sendMessageToApi,
    openChat,
    get state() { return { chats, activeChatId, chatHistories }; }
  };
});
