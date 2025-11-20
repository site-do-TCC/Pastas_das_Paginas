// ==================== MENU RESPONSIVO ====================
const menuBtn = document.getElementById("menu-btn");
const menu = document.getElementById("menu");
if (menuBtn && menu) {
  menuBtn.addEventListener("click", () => menu.classList.toggle("show"));
}

document.addEventListener('DOMContentLoaded', () => {
  const chatListEl = document.getElementById("chat-list");
  const messagesEl = document.getElementById("chat-messages");
  const userNameEl = document.getElementById("chat-user-name");
  const userPhotoEl = document.getElementById("chat-user-photo");
  const userStatusEl = document.getElementById("chat-user-status");
  const searchInput = document.getElementById("search-input");
  const messageInput = document.getElementById("message-input");
  const sendBtn = document.getElementById("send-btn");
  const attachBtn = document.getElementById("attach-btn");
  const fileInput = document.getElementById("file-input");


  let chats = [];
  let activeChatId = null;
  let chatHistories = {};
  let currentUserId = null;
  let pollingTimer = null;
  let lastMsgIdMap = {};
  let heartbeatTimer = null;
  let presencePollTimer = null;
  let dotsTimer = null;
  let dotsStep = 0;
  let lastTypingPing = 0;
  let typingAnimating = false;

  // tenta restaurar estado salvo no navegador
  try {
    const saved = JSON.parse(localStorage.getItem("chat_state"));
    if (saved) {
      chats = saved.chats || [];
      chatHistories = saved.chatHistories || {};
      activeChatId = saved.activeChatId || null;
      lastMsgIdMap = saved.lastMsgIdMap || {};
    }
  } catch (_) {}
  // Sincroniza com window para quem usa fora do escopo
  window.chats = chats;
  window.chatHistories = chatHistories;
  window.activeChatId = activeChatId;

  function saveState() {
    localStorage.setItem(
      "chat_state",
      JSON.stringify({ chats, chatHistories, activeChatId, lastMsgIdMap })
    );
  }

  async function sendAttachment(idPara, file, textoOptional='') {
    try {
      const fd = new FormData();
      fd.append('id_para', idPara);
      if (textoOptional) fd.append('texto', textoOptional);
      fd.append('arquivo', file);
      const resp = await fetch('/Programacao_TCC_Avena/php/sendAttachment.php', {
        method: 'POST',
        credentials: 'same-origin',
        body: fd
      });
      return await resp.json();
    } catch (e) {
      console.error('sendAttachment erro:', e);
      return { ok:false, erro:e.message };
    }
  }

  // Helper: verifica se a última mensagem conhecida do chat veio de mim
  function lastMessageIsFromMe(chatId, lastId) {
    try {
      const hist = chatHistories[chatId];
      if (!Array.isArray(hist) || hist.length === 0) return false;
      const last = hist[hist.length - 1];
      if (lastId && typeof lastId === 'number') {
        // se temos id, tenta achar exatamente
        const found = hist.find(m => Number(m.id) === Number(lastId));
        if (found) return found.from === 'me';
      }
      // fallback: compara com o último conhecido
      return last.from === 'me';
    } catch (_) { return false; }
  }

  function escapeHtml(str) {
    if (!str) return "";
    return str
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  // Converte URLs simples em links estilizados; evita quebrar HTML usando escape antes
  function linkify(raw) {
    if (!raw) return '';
    // Mantém newlines
    const parts = raw.split(/(https?:\/\/[^\s]+)/g);
    return parts.map(p => {
      if (/^https?:\/\//i.test(p)) {
        const safe = escapeHtml(p);
        return `<span class="link-card" data-url="${safe}"><a href="${safe}" target="_blank" rel="noopener">${safe}</a></span>`;
      }
      return escapeHtml(p);
    }).join('');
  }

  // fetchChatList: manter lastMessage atualizado e sinalizar não lidas
  async function fetchChatList() {
    const resp = await fetch('/Programacao_TCC_Avena/php/getChatList.php', { credentials:'same-origin', cache:'no-store' });
    if (!resp.ok) throw new Error('HTTP ' + resp.status + ' - ' + (await resp.text()));
    const data = await resp.json();
    if (!data || !data.ok) throw new Error('fetchChatList erro: ' + (data?.erro || 'retorno inválido'));

    const prev = window.chats || [];
    const incoming = Array.isArray(data.chats) ? data.chats : [];
    const updated = incoming.map(nc => {
      const exist = prev.find(p => p.id === nc.id) || {};
      const id = nc.id, isActive = (id === window.activeChatId);
      const lastId   = nc.lastMessageId ?? exist.lastMessageId ?? null;
      const lastText = nc.lastMessage   ?? exist.lastMessage   ?? '';
      const lastTime = nc.lastMessageTime ?? exist.lastMessageTime ?? null;

      // Se estou no chat ativo e há msg nova, considero lida agora
      const lr0 = getLastReadId(id);
      if (isActive && lastId && lastId > lr0) setLastReadId(id, lastId);

      const lastRead = getLastReadId(id);
      const lastSent = getLastSentId(id);

      // Não lidas: só fora do chat ativo, depois do lastRead e não sendo sua própria última
      const wasUnread = Number(exist.unread || 0) > 0;
      const nowUnread = (!isActive && lastId && lastId > lastRead && lastId !== lastSent) ? 1 : 0;

      if (!wasUnread && nowUnread) window.__SND__?.playNew();

      return {
        id,
        name: nc.name,
        photo: nc.photo,
        online: !!nc.online,
        lastMessage: lastText,
        lastMessageId: lastId,
        lastMessageTime: lastTime,
        unread: nowUnread,
        hasNewMessage: !!nowUnread
      };
    });

    window.chats = updated.length ? updated : prev;
    window.renderChatList?.(window.chats);

    // Se o ativo avançou no servidor, traga só novas e marque lido
    const a = window.activeChatId && (window.chats || []).find(c => c.id === window.activeChatId);
    if (a) {
      const hist = (window.chatHistories || {})[a.id] || [];
      const lastLocalId = hist.length ? (hist[hist.length-1].id || 0) : 0;
      if (a.lastMessageId && a.lastMessageId > lastLocalId && typeof refreshActiveChatOnce === 'function') {
        await refreshActiveChatOnce();
      }
    }
    return window.chats;
  }

  async function fetchOpenChat(otherId) {
    try {
      const resp = await fetch(
        `/Programacao_TCC_Avena/php/openChat.php?other_id=${encodeURIComponent(
          otherId
        )}`,
        { credentials: "same-origin" }
      );
      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
      return await resp.json();
    } catch (err) {
      console.error("fetchOpenChat erro:", err);
      return null;
    }
  }

  async function sendMessage(idPara, conteudo) {
    try {
      const body = new URLSearchParams();
      body.append("id_para", idPara);
      body.append("conteudo", conteudo);
      const resp = await fetch("/Programacao_TCC_Avena/php/sendMessage.php", {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body,
      });
      return await resp.json();
    } catch (err) {
      console.error("sendMessage erro:", err);
      return { ok: false, erro: err.message };
    }
  }

  // renderChatList (otimizado): faz diff incremental para não reiniciar animações
  function renderChatList(list) {
    const chatListEl = document.getElementById('chat-list');
    if (!chatListEl) return;

    const desiredIds = new Set(list.map(c => String(c.id)));

    // Atualiza ou cria cada item
    list.forEach(chat => {
      const idStr = String(chat.id);
      let item = chatListEl.querySelector(`.chat-item[data-chat-id='${CSS.escape(idStr)}']`);
      if (!item) {
        item = document.createElement('div');
        item.className = 'chat-item';
        item.dataset.chatId = idStr;
        chatListEl.appendChild(item);
        // bind click uma vez
        item.addEventListener('click', () => {
          const lastId = chat.lastMessageId || 0;
          if (lastId) setLastReadId(chat.id, lastId);
          const c = (chats || []).find(x => x.id === chat.id);
          if (c) { c.unread = 0; c.hasNewMessage = false; }
          // Não força rerender total; openChat fará ajustes necessários
          window.openChat?.(chat.id, { force: true });
        });
      }

      // estado ativo
      if (chat.id === activeChatId) item.classList.add('active'); else item.classList.remove('active');

      // avatar
      let avatar = item.querySelector('.avatar');
      if (!avatar) {
        avatar = document.createElement('div');
        avatar.className = 'avatar';
        item.appendChild(avatar);
      }
      const bgUrl = chat.photo || '../img/SemFoto.jpg';
      if (!avatar.__bg || avatar.__bg !== bgUrl) {
        avatar.style.backgroundImage = `url('${bgUrl}')`;
        avatar.__bg = bgUrl;
      }

      // meta (nome + preview)
      let meta = item.querySelector('.meta');
      const nameHtml = escapeHtml(chat.name);
      const msgHtml = escapeHtml(chat.lastMessage || '');
      const metaHtml = `<h4>${nameHtml}</h4><p>${msgHtml}</p>`;
      if (!meta) {
        meta = document.createElement('div');
        meta.className = 'meta';
        meta.innerHTML = metaHtml;
        item.appendChild(meta);
      } else if (meta.__html !== metaHtml) {
        meta.innerHTML = metaHtml;
        meta.__html = metaHtml;
      }

      // bolinha não lida
      const shouldShowDot = chat.id !== activeChatId && ((chat.unread ?? 0) > 0 || chat.hasNewMessage);
      let dot = item.querySelector('.chat-dot');
      if (shouldShowDot && !dot) {
        dot = document.createElement('span');
        dot.className = 'chat-dot';
        item.appendChild(dot);
      } else if (!shouldShowDot && dot && !dot.classList.contains('fade-out')) {
        // anima desaparecer e remove após transição
        dot.classList.add('fade-out');
        const removeFn = () => { dot?.remove(); };
        dot.addEventListener('transitionend', removeFn, { once: true });
        // fallback caso transitionend não dispare
        setTimeout(removeFn, 600);
      }
    });

    // Remove itens que não estão mais na lista
    [...chatListEl.querySelectorAll('.chat-item')].forEach(item => {
      const id = item.dataset.chatId;
      if (!desiredIds.has(id)) item.remove();
    });
  }

  function renderMessages(messages) {
    if (!messagesEl) return;
    messagesEl.innerHTML = "";
    messages.forEach((m) => {
      const div = document.createElement('div');
      div.classList.add('msg', m.from === 'me' ? 'outgoing' : 'incoming');
      div.style.whiteSpace = 'pre-wrap';
      div.style.wordBreak = 'break-word';
      div.style.overflowWrap = 'break-word';

      // Renderização por tipo
      const tipo = m.tipo || 'text';
      let filePath = m.arquivo || null;
      // Corrige caminhos relativos antigos (sem /Programacao_TCC_Avena/ prefix)
      if (filePath && !/^https?:\/\//i.test(filePath)) {
        if (!filePath.startsWith('/Programacao_TCC_Avena/')) {
          // casos antigos armazenados como 'uploads/messages/...' ou '../uploads/messages/...'
          filePath = filePath.replace(/^\.\.\/?/, '');
          if (filePath.startsWith('uploads/')) {
            filePath = '/Programacao_TCC_Avena/' + filePath;
          }
        }
      }
      // Fallback: tentar extrair marcador se arquivo ausente mas conteudo tem anexo
      if (!filePath && tipo === 'text' && typeof m.text === 'string') {
        const markerMatch = m.text.match(/\[\[ATTACH:type=([^;]+);file=([^\]]+)\]\]/);
        if (markerMatch) {
          const mkTipo = markerMatch[1];
          let mkFile = markerMatch[2];
          if (mkFile && !mkFile.startsWith('/Programacao_TCC_Avena/')) {
            if (mkFile.startsWith('uploads/')) mkFile = '/Programacao_TCC_Avena/' + mkFile;
          }
          filePath = mkFile; m.tipo = mkTipo; // atualiza em runtime
          m.text = m.text.replace(/\[\[ATTACH:type=[^;]+;file=[^\]]+\]\]/,'').trim();
        }
      }
      if (tipo === 'image' && filePath) {
        div.classList.add('attachment');
        const wrap = document.createElement('div');
        wrap.className = 'image-attachment';
        const img = document.createElement('img');
        img.src = filePath;
        img.alt = 'imagem';
        img.className = 'image-el';
        wrap.appendChild(img);
        if (m.text && m.text.trim() !== '') {
          const cap = document.createElement('div'); cap.className='att-caption'; cap.textContent = m.text.trim(); wrap.appendChild(cap);
        }
        div.appendChild(wrap);
      } else if (tipo === 'video' && filePath) {
        div.classList.add('attachment');
        const wrap = document.createElement('div');
        wrap.className = 'video-attachment';
        const vid = document.createElement('video');
        vid.src = filePath;
        vid.controls = true;
        vid.className = 'video-el';
        wrap.appendChild(vid);
        // legenda somente se usuário forneceu texto
        if (m.text && m.text.trim() !== '') {
          const cap = document.createElement('div'); cap.className='att-caption'; cap.textContent = m.text.trim(); wrap.appendChild(cap);
        }
        div.appendChild(wrap);
      } else if (tipo === 'audio' && filePath) {
        div.classList.add('attachment');
        const wrap = document.createElement('div');
        wrap.className = 'audio-attachment';
        const aud = document.createElement('audio'); aud.src = filePath; aud.controls = true; aud.className='audio-el';
        wrap.appendChild(aud);
        if (m.text && m.text.trim() !== '') { const cap = document.createElement('div'); cap.className='att-caption'; cap.textContent = m.text.trim(); wrap.appendChild(cap); }
        div.appendChild(wrap);
      } else if (tipo === 'file' && filePath) {
        const wrap = document.createElement('div');
        wrap.className = 'file-attachment';
        const icon = document.createElement('span');
        icon.className = 'fa-icon';
        const fname = (filePath.split('/').pop() || '').toLowerCase();
        const ext = fname.split('.').pop() || '';
        icon.textContent = ext === 'pdf' ? '📄' : (['jpg','jpeg','png','gif','webp'].includes(ext) ? '🖼️' : '📦');
        const link = document.createElement('a');
        link.href = filePath; link.target = '_blank'; link.rel='noopener';
        link.className = 'file-name';
        link.textContent = m.text && m.text.trim() !== '' ? m.text.trim() : fname;
        const meta = document.createElement('span');
        meta.className = 'file-meta';
        if (m.tamanho) {
          const kb = Math.max(1, Math.round(m.tamanho/1024));
          meta.textContent = kb + ' KB';
        } else {
          meta.textContent = ext.toUpperCase();
        }
        wrap.appendChild(icon);
        wrap.appendChild(link);
        wrap.appendChild(meta);
        div.appendChild(wrap);
      } else { // texto normal
        const html = linkify(m.text || m.conteudo || '');
        div.innerHTML = html;
      }
      messagesEl.appendChild(div);
    });
    messagesEl.scrollTop = messagesEl.scrollHeight;
    upgradeLinkCards();
  }

  // Expor funções para uso externo (refreshActiveChatOnce etc.)
  window.renderMessages = renderMessages;
  window.renderChatList = renderChatList;

  async function openChat(otherId) {
    if (!otherId) return;
    const data = await fetchOpenChat(otherId);
    if (!data || !data.ok) return;

    currentUserId = data.current_user_id ?? null;
    const chat = chats.find((c) => c.id === otherId) || {
      id: otherId,
      name: data.other?.nome ?? "Usuário",
    };
    activeChatId = chat.id;
    window.activeChatId = activeChatId; // <- mantém global

    const messages = (data.messages || []).map((m) => ({
      id: m.id,
      from: m.de === currentUserId ? "me" : "them",
      text: m.conteudo,
      enviado_em: m.enviado_em,
      // Inclui campos de anexo para não perder ao trocar de chat
      tipo: m.tipo || 'text',
      arquivo: m.arquivo || null
    }));

    chatHistories[otherId] = messages;
    window.chatHistories = chatHistories; // <- mantém global
    renderMessages?.(messages);

    // Atualiza cabeçalho imediatamente para evitar 'layout do outro'
    const headerName = data.other?.nome || chat.name || "";
    const headerPhoto = data.other?.photo || chat.photo || "../img/SemFoto.jpg";
    if (userNameEl) userNameEl.textContent = headerName;
    if (userPhotoEl) userPhotoEl.style.backgroundImage = `url('${headerPhoto}')`;

    const lastId = Array.isArray(messages) && messages.length ? (messages[messages.length-1].id || 0) : 0;
    if (lastId) setLastReadId(otherId, lastId);
    const c = (chats || []).find(x => x.id === otherId);
    if (c) { c.unread = 0; c.hasNewMessage = false; }
    renderChatList(chats || []);
    window.chats = chats; // <- mantém global
    // reinicia polling de presença do outro usuário
    startPresencePolling();
    saveState();
  }

  // Expor openChat com guarda contra auto-switch (só abre se for ação do usuário ou se já for o chat ativo)
  window.openChat = async function(otherId, opts = {}) {
    const isUserAction = !!opts.force || (window.__USER_NAV_TS && (Date.now() - window.__USER_NAV_TS) < 1000);
    if (!isUserAction && otherId !== activeChatId) return;

    // Antes de trocar de chat, marque o chat atual como lido até o último conhecido
    const prevActive = activeChatId;
    if (prevActive && prevActive !== otherId) {
      let lastKnown = 0;
      const hist = chatHistories[prevActive];
      if (Array.isArray(hist) && hist.length) lastKnown = Number(hist[hist.length-1].id || 0);
      if (!lastKnown) {
        const row = (chats || []).find(c => c.id === prevActive);
        if (row && row.lastMessageId) lastKnown = Number(row.lastMessageId);
      }
      if (lastKnown && typeof setLastReadId === 'function') setLastReadId(prevActive, lastKnown);
      // Atualiza cache local para não tocar som nem mostrar bolinha no chat que está sendo deixado
      if (lastKnown) {
        lastMsgIdMap[prevActive] = lastKnown;
      }
      const row = (chats || []).find(c => c.id === prevActive);
      if (row) {
        row.unread = 0;
        row.hasNewMessage = false;
      }
      renderChatList?.(chats || []);
    }

    return openChat(otherId);
  };

  if (sendBtn) {
    sendBtn.addEventListener("click", async () => {
      // tenta desbloquear som no gesto do usuário
      try { window.__SND__?.unlock?.(); } catch {}
      const text = messageInput.value.trim();
      if (!text || !activeChatId) return;

      const result = await sendMessage(activeChatId, text);
      if (result.ok) {
        const msg = {
          id: result.id_mensagem,
          from: "me",
          text,
          enviado_em: result.enviado_em,
        };
        chatHistories[activeChatId] = chatHistories[activeChatId] || [];
        chatHistories[activeChatId].push(msg);
        renderMessages(chatHistories[activeChatId]);

        const chat = chats.find((c) => c.id === activeChatId);
        if (chat) chat.lastMessage = text;
        // Atualiza último id para evitar tocar som ao próximo poll
        if (result.id_mensagem) {
          lastMsgIdMap[activeChatId] = result.id_mensagem;
          // Marca como lido localmente para não aparecer bolinha ao sair e voltar
          setLastReadId(activeChatId, result.id_mensagem);
        }
        renderChatList(chats);
        messageInput.value = "";
        // após salvar no servidor...
        window.onMessageSent?.(activeChatId, (result?.id_mensagem || result?.id));
        saveState();
      }
    });
  }

  // ===== Link Preview (OpenGraph) =====
  function upgradeLinkCards(){
    const cards = messagesEl?.querySelectorAll('.link-card[data-url]:not([data-preview])');
    if (!cards || !cards.length) return;
    cards.forEach(card => {
      const url = card.getAttribute('data-url');
      if (!url) return;
      card.setAttribute('data-preview','loading');
      const cacheKey = 'lp_'+url;
      try {
        const cached = sessionStorage.getItem(cacheKey);
        if (cached) { renderPreview(card, JSON.parse(cached)); return; }
      } catch {}
      fetch('/Programacao_TCC_Avena/php/linkPreview.php?url='+encodeURIComponent(url), { credentials:'same-origin' })
        .then(r => r.ok ? r.json() : null)
        .then(data => {
          if (data && data.ok) {
            try { sessionStorage.setItem(cacheKey, JSON.stringify(data)); } catch {}
            renderPreview(card, data);
          } else {
            card.removeAttribute('data-preview');
          }
        })
        .catch(()=>{ card.removeAttribute('data-preview'); });
    });
  }

  function renderPreview(card, data){
    if (!card || !data) return;
    card.classList.add('link-preview');
    const title = escapeHtml(data.title || data.url || 'Link');
    const desc  = escapeHtml((data.description || '').slice(0,140));
    const img   = data.image && /^https?:\/\//i.test(data.image) ? data.image : null;
    const a = card.querySelector('a');
    const url = a ? a.getAttribute('href') : (data.url || '');
    card.innerHTML = `
      <a href="${escapeHtml(url)}" target="_blank" rel="noopener" class="lp-wrap">
        ${img ? `<div class="lp-thumb" style="background-image:url('${escapeHtml(img)}')"></div>` : ''}
        <div class="lp-text">
          <div class="lp-title">${title}</div>
          ${desc ? `<div class="lp-desc">${desc}</div>` : ''}
          <div class="lp-host">${escapeHtml(hostFromUrl(url))}</div>
        </div>
      </a>`;
  }

  function hostFromUrl(u){ try { return new URL(u).host.replace(/^www\./,''); } catch { return ''; } }

  // Botão de anexar: apenas abre o seletor de arquivos por enquanto
  if (attachBtn && fileInput) {
    attachBtn.addEventListener('click', () => {
      try { window.__SND__?.unlock?.(); } catch {}
      fileInput.click();
    });
    fileInput.addEventListener('change', async () => {
      if (!fileInput.files || !fileInput.files.length || !activeChatId) return;
      const file = fileInput.files[0];
      console.log('[chat] upload iniciando:', file.name, file.type, file.size);
      const placeholder = { id: 'temp_'+Date.now(), from:'me', tipo:'file', arquivo:null, text:'Enviando '+file.name+'...' };
      chatHistories[activeChatId] = chatHistories[activeChatId] || [];
      chatHistories[activeChatId].push(placeholder);
      renderMessages(chatHistories[activeChatId]);
      const r = await sendAttachment(activeChatId, file, '');
      // Remove placeholder
      const hist = chatHistories[activeChatId];
      const idx = hist.findIndex(x => x.id === placeholder.id);
      if (idx >= 0) hist.splice(idx,1);
      if (r.ok) {
        const msg = { id:r.id_mensagem, from:'me', text:r.texto||'', tipo:r.tipo, arquivo:r.arquivo, enviado_em:r.enviado_em, tamanho:r.tamanho };
        hist.push(msg);
        const chat = chats.find(c=>c.id===activeChatId); if (chat) chat.lastMessage = msg.text || ('['+msg.tipo+']');
        renderMessages(hist);
        window.onMessageSent?.(activeChatId, r.id_mensagem);
      } else {
        hist.push({ id:'fail_'+Date.now(), from:'me', text:'Falha: '+(r.erro||'erro upload') });
        renderMessages(hist);
      }
      fileInput.value='';
    });
  }

  if (messageInput) {
    messageInput.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        try { window.__SND__?.unlock?.(); } catch {}
        sendBtn.click();
      }
    });
  }

  async function pollChats() {
    try {
      const latest = await fetchChatList();
      if (!Array.isArray(latest)) return;
      chats = latest;
      window.chats = chats; // <- mantém global

      const active = chats.find(c => c.id === activeChatId);
      if (active) {
        const localHist = chatHistories[active.id];
        if (localHist && localHist.length > 0) {
          active.lastMessage = localHist[localHist.length - 1].text;
        }
      }

      // Notificações ao detectar mudança de último id em cada chat
      chats.forEach((c) => {
        const cid = c.id;
        const newId = c.lastMessageId || null;
        if (newId) {
          const prevId = lastMsgIdMap[cid];
          if (prevId !== newId) {
            if (cid !== activeChatId) {
              const lastRead = getLastReadId(cid);
              if (newId > lastRead && !lastMessageIsFromMe(cid, newId)) {
                window.__SND__?.playNew();
              }
            }
          }
          lastMsgIdMap[cid] = newId;
        }
      });
      renderChatList(chats);

      if (activeChatId) {
        const open = await fetchOpenChat(activeChatId);
        if (open && open.ok) {
          if (typeof open.current_user_id !== "undefined" && open.current_user_id !== null) {
            currentUserId = open.current_user_id;
          }
          const incoming = (open.messages || []).map(m => ({
            id: m.id,
            from: m.de === currentUserId ? 'me' : 'them',
            text: m.conteudo,
            enviado_em: m.enviado_em,
            tipo: m.tipo || 'text',
            arquivo: m.arquivo || null
          }));
          const existing = chatHistories[activeChatId] || [];
          const seen = new Set(existing.map(x => x.id));
          const appended = [];
          for (const msg of incoming) {
            if (!seen.has(msg.id)) {
              existing.push(msg);
              appended.push(msg);
            }
          }
          // Replace history with merged version
          chatHistories[activeChatId] = existing;
          if (appended.length > 0) {
            renderMessages(existing);
            const last = existing[existing.length - 1];
            if (last && last.id) {
              // Som somente se a nova última veio do outro
              if (appended.some(a => a.from === 'them')) {
                if (document.hidden) window.__SND__?.playNew(); else window.__SND__?.playSame();
              }
              lastMsgIdMap[activeChatId] = last.id;
              setLastReadId(activeChatId, last.id);
            }
          }
          // Atualiza header
          const chat = chats.find(c => c.id === activeChatId);
          const name = (open.other && open.other.nome) || (chat && chat.name) || '';
          const photo = (open.other && open.other.photo) || (chat && chat.photo) || "../img/SemFoto.jpg";
          if (userNameEl) userNameEl.textContent = name;
          if (userPhotoEl) userPhotoEl.style.backgroundImage = `url('${photo}')`;
        }
      }
      saveState();
    } catch (err) {
      console.error("pollChats erro:", err);
    }
  }

  function startPolling() {
    if (pollingTimer) clearInterval(pollingTimer);
    pollingTimer = setInterval(pollChats, 1000);
  }

  // =============== PRESENÇA (online/digitando) ===============
  async function presenceUpdate(typing) {
    try {
      const body = new URLSearchParams();
      if (typeof typing === "number") body.append("typing", String(typing));
      await fetch("/Programacao_TCC_Avena/php/presence_update.php", {
        method: "POST",
        credentials: "same-origin",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body,
      });
    } catch (e) {
      // silencioso
    }
  }

  async function presenceGet(otherId) {
    try {
      if (!otherId) return { ok: false };
      const resp = await fetch(
        `/Programacao_TCC_Avena/php/presence_get.php?other_id=${encodeURIComponent(
          otherId
        )}`,
        { credentials: "same-origin" }
      );
      if (!resp.ok) return { ok: false };
      return await resp.json();
    } catch (_) {
      return { ok: false };
    }
  }

  function setStatusText(text) {
    if (!userStatusEl) return;
    userStatusEl.textContent = text || "";
  }

  function stopDots() {
    if (dotsTimer) {
      clearInterval(dotsTimer);
      dotsTimer = null;
    }
    dotsStep = 0;
    typingAnimating = false;
  }

  function startDots(baseText) {
    if (!userStatusEl) return;
    if (typingAnimating) return; // não reinicia se já está animando
    stopDots();
    typingAnimating = true;
    // Ciclo desejado: Digitando. Digitando.. Digitando...
    const states = ['.', '..', '...'];
    let idx = 0;
    userStatusEl.textContent = `${baseText}${states[idx]}`;
    dotsTimer = setInterval(() => {
      idx = (idx + 1) % states.length;
      userStatusEl.textContent = `${baseText}${states[idx]}`;
    }, 550); // ligeiramente mais lento
  }

  async function pollPresenceOnce() {
    if (!activeChatId) {
      stopDots();
      setStatusText("");
      return;
    }
    const res = await presenceGet(activeChatId);
    if (!res || !res.ok) return;
    if (userStatusEl) userStatusEl.classList.remove('online','typing');
    if (res.typing) {
      startDots("Digitando");
      if (userStatusEl) userStatusEl.classList.add('typing');
    } else if (res.online) {
      stopDots();
      setStatusText("online");
      if (userStatusEl) userStatusEl.classList.add('online');
    } else {
      stopDots();
      setStatusText(""); // permanece oculto
    }
  }

  function startPresencePolling() {
    if (presencePollTimer) clearInterval(presencePollTimer);
    // faz um ping imediato
    pollPresenceOnce();
    presencePollTimer = setInterval(pollPresenceOnce, 1000);
  }

  (async function init() {
    const latest = await fetchChatList();
    if (latest.length > 0) chats = latest;
    renderChatList(chats);
    if (activeChatId) {
      renderMessages(chatHistories[activeChatId] || []);
    }
    startPolling();
    // Inicia heartbeat do usuário atual para marcar "online"
    if (heartbeatTimer) clearInterval(heartbeatTimer);
    await presenceUpdate(); // ping inicial
    heartbeatTimer = setInterval(() => presenceUpdate(), 20000);
    // typing: envia sinal quando o usuário digita
    if (messageInput) {
      messageInput.addEventListener("input", () => {
        const now = Date.now();
        if (now - lastTypingPing > 2500) {
          lastTypingPing = now;
          presenceUpdate(1);
        }
      });
    }

    // Ping presença a cada 20s + typing quando digita
    if (!window.__presenceTimer) {
      window.__presenceTimer = setInterval(() => {
        fetch('/Programacao_TCC_Avena/php/statusPing.php', { method:'POST', credentials:'same-origin' }).catch(()=>{});
      }, 20000);
      fetch('/Programacao_TCC_Avena/php/statusPing.php', { method:'POST', credentials:'same-origin' }).catch(()=>{});
    }
    const input = document.querySelector('#message-input, #msg-input, textarea[name="mensagem"]');
    if (input && !input.__typingBound) {
      input.__typingBound = true;
      let lastSent = 0;
      input.addEventListener('input', () => {
        const now = Date.now(); if (now - lastSent < 1200) return; lastSent = now;
        const fd = new FormData(); fd.append('typing','1');
        fetch('/Programacao_TCC_Avena/php/statusPing.php', { method:'POST', body: fd, credentials:'same-origin' }).catch(()=>{});
      });
    }

    // Atualiza status do ativo a cada 1s
    if (!window.__statusTimer) {
      window.__statusTimer = setInterval(async () => {
        const id = window.activeChatId; if (!id) return;
        try {
          const r = await fetch(`/Programacao_TCC_Avena/php/getStatus.php?other_id=${id}&t=${Date.now()}`, { credentials:'same-origin', cache:'no-store' });
          if (!r.ok) return; const st = await r.json(); if (!st?.ok) return;
          const headerStatus = document.getElementById('chat-user-status') || document.querySelector('.chat-header .status');
          if (headerStatus) {
            headerStatus.classList.remove('online','typing');
            if (st.typing) {
              // inicia se não estiver rodando
              startDots('Digitando');
              headerStatus.classList.add('typing');
            } else if (st.online) {
              stopDots();
              headerStatus.textContent = 'online';
              headerStatus.classList.add('online');
            } else {
              stopDots();
              headerStatus.textContent = '';
            }
          }
          const row = (window.chats || []).find(c => c.id === id);
          if (row) { row.online = !!st.online; window.renderChatList?.(window.chats || []); }
        } catch {}
      }, 1000);
    }
  })();

  // Ao clicar no item da lista, marque navegação do usuário
  if (chatListEl && !chatListEl.__guardClick) {
    chatListEl.__guardClick = true;
    chatListEl.addEventListener('click', (ev) => {
      const item = ev.target.closest('.chat-item');
      if (!item) return;
      try { window.__SND__?.unlock?.(); } catch {}
      window.__USER_NAV_TS = Date.now();
      const id = Number(item.dataset.chatId);
      if (id) window.openChat?.(id, { force: true });
    });
  }


  // Sons WAV (unlock silencioso + fallback beep)
  (function initSoundsWav(){
    if (window.__SND__) return;
    const newSrc  = '/Programacao_TCC_Avena/sounds/NovaMensagem.wav';
    const sameSrc = '/Programacao_TCC_Avena/sounds/NovaMensagemMesmoChat.wav';
    const aNew  = new Audio(newSrc);  aNew.preload='auto';  aNew.volume=0.28;
    const aSame = new Audio(sameSrc); aSame.preload='auto'; aSame.volume=0.28;

    const AC = window.AudioContext || window.webkitAudioContext;
    const ctx = AC ? new AC() : null;
    let unlocked = false;

    async function unlockOnce(){
      if (unlocked) return;
      try {
        if (ctx?.state === 'suspended') { try { await ctx.resume(); } catch {} }
        for (const a of [aNew, aSame]) {
          try { a.muted = true; a.currentTime = 0; await a.play(); a.pause(); a.currentTime = 0; a.muted = false; } catch {}
        }
      } finally {
        unlocked = true;
      }
    }
    ['pointerdown','click','keydown','touchstart'].forEach(e => window.addEventListener(e, unlockOnce, { once:true }));

    function beep(freq=880, dur=0.12, vol=0.18){
      if (!ctx) return;
      const t0 = ctx.currentTime, osc = ctx.createOscillator(), g = ctx.createGain();
      osc.type = 'sine'; osc.frequency.value = freq;
      g.gain.setValueAtTime(0.0001,t0);
      g.gain.exponentialRampToValueAtTime(vol,t0+0.01);
      g.gain.exponentialRampToValueAtTime(0.0001,t0+dur);
      osc.connect(g); g.connect(ctx.destination);
      osc.start(t0); osc.stop(t0+dur);
    }
    async function play(tag){
      const au = tag === 'new' ? aNew : aSame;
      try { au.currentTime = 0; await au.play(); }
      catch { beep(tag === 'new' ? 920 : 620); }
    }
    window.__SND__ = { playNew: ()=>play('new'), playSame: ()=>play('same') };
  })();

  // Estado local: lastRead/lastSent (para bolinha e som)
  (function initReadMaps(){
    if (window.getLastReadId) return;
    const LS_READ='chatLastReadId', LS_SENT='chatLastSentId';
    function load(k){ try { return JSON.parse(localStorage.getItem(k)||'{}'); } catch { return {}; } }
    function save(k,m){ localStorage.setItem(k, JSON.stringify(m)); }
    window.__lastRead = load(LS_READ);
    window.__lastSent = load(LS_SENT);
    window.getLastReadId = id => Number(window.__lastRead[id] || 0);
    window.setLastReadId = (id,val) => { window.__lastRead[id] = Number(val)||0; save(LS_READ, window.__lastRead); };
    window.getLastSentId = id => Number(window.__lastSent[id] || 0);
    window.setLastSentId = (id,val) => { window.__lastSent[id] = Number(val)||0; save(LS_SENT, window.__lastSent); };
    window.onMessageSent = (chatId, msgId) => { if (!chatId || !msgId) return; setLastSentId(chatId, msgId); setLastReadId(chatId, msgId); };
  })();

  // Último lido (client-side) — controla a bolinha roxa
  if (!window.__lastRead) {
    const LS_KEY='chatLastReadId';
    function load(){ try { return JSON.parse(localStorage.getItem(LS_KEY)||'{}'); } catch { return {}; } }
    function save(m){ localStorage.setItem(LS_KEY, JSON.stringify(m)); }
    window.__lastRead = load();
    window.getLastReadId = (id)=>Number(window.__lastRead[id]||0);
    window.setLastReadId = (id,val)=>{ window.__lastRead[id]=Number(val)||0; save(window.__lastRead); };
  }
  // Último ENVIADO por chat (evita tocar "NovaMensagem.wav" pela sua própria msg)
  if (!window.__lastSent) window.__lastSent = {};
  function getLastSentId(chatId){ return Number(window.__lastSent[chatId] || 0); }
  function setLastSentId(chatId, id){ window.__lastSent[chatId] = Number(id)||0; }

  // Callback global para ser chamado após enviar mensagem com sucesso
  window.onMessageSent = function(chatId, newMsgId){
    if (!chatId || !newMsgId) return;
    setLastSentId(chatId, newMsgId);
    // marca como lido para você (não gerar bolinha do seu próprio envio)
    if (typeof setLastReadId === 'function') setLastReadId(chatId, newMsgId);
  };

});
async function refreshActiveChatOnce() {
  const activeId = window.activeChatId; // garantir uso da var global
  if (!activeId) return;
  try {
    const url = `/Programacao_TCC_Avena/php/openChat.php?other_id=${encodeURIComponent(activeId)}&t=${Date.now()}`;
    const r = await fetch(url, { credentials:'same-origin', cache:'no-store' });
    if (!r.ok) return;
    const data = await r.json();
    if (!data || !data.ok) return;

    const me = Number(data.current_user_id ?? 0);
    const remote = (data.messages || []).map(m => ({
      id: m.id ?? m.id_mensagem,
      from: Number(m.de ?? m.id_de) === me ? 'me' : 'them',
      text: String(m.conteudo ?? '').trim(),
      enviado_em: m.enviado_em || null,
      tipo: m.tipo || 'text',
      arquivo: m.arquivo || null
    }));

    window.chatHistories = window.chatHistories || {};
  const hist = window.chatHistories[activeId] || [];
    const last = hist[hist.length - 1] || null;

    let newMsgs = [];
    if (!last) newMsgs = remote;
    else if (last.id != null) newMsgs = remote.filter(x => x.id != null && x.id > last.id);
    else if (last.enviado_em) newMsgs = remote.filter(x => x.enviado_em && x.enviado_em > last.enviado_em);
    if (!newMsgs.length) return;

    const merged = hist.concat(newMsgs);
  window.chatHistories[activeId] = merged;
  window.renderMessages?.(merged);

    // Som no mesmo chat (apenas recebidas e aba visível)
    if (newMsgs.some(x => x.from === 'them') && !document.hidden) window.__SND__?.playSame();

    // Marca como lido até a última
    const lastIdAll = merged[merged.length-1]?.id || 0;
  if (lastIdAll) setLastReadId(activeId, lastIdAll);

    // Atualiza preview e limpa bolinha do ativo
    const row = (window.chats || []).find(c => c.id === activeId);
    if (row) { row.lastMessage = merged[merged.length-1]?.text || row.lastMessage; row.unread = 0; row.hasNewMessage = false; }
    window.renderChatList?.(window.chats || []);
  } catch {}
}
