<div class="page-header header-bg-account">
    <div class="header-content">
        <h1>Mon compte</h1>
        <div class="breadcrumb">
            <a href="index.php?action=home">Accueil</a> > Mon compte
        </div>
    </div>
</div>

<div class="account-container">
    <div class="account-sidebar">
        <!-- User Summary Box -->
        <div class="user-summary">
            <div class="user-info">
                <?php $avatar = $user->getImage() ? $user->getImage() : "img/avatar-default.png"; ?>
                <img src="<?= $avatar ?>" alt="Avatar" class="avatar" style="cursor: pointer;"
                    onclick="document.getElementById('avatar-admin-input').click();">
                <form action="index.php?action=updateAccount" method="post" enctype="multipart/form-data"
                    style="display: none;">
                    <input type="hidden" name="redirect_to" value="adminMessages">
                    <input type="file" name="avatar" id="avatar-admin-input" onchange="this.form.submit()">
                </form>
            </div>
            <a href="index.php?action=disconnectUser" class="logout-link">Déconnexion</a>
        </div>

        <!-- Navigation -->
        <nav class="account-nav">
            <a href="index.php?action=compte" class="account-nav-item">
                <img src="img/icone-compte.png" alt="Infos">
                Informations
            </a>
            <a href="index.php?action=adminMessages" class="account-nav-item active">
                <img src="img/icone-messagerie-active.png" alt="Messagerie">
                Messagerie
            </a>
        </nav>
    </div>

    <div class="account-content">
        <!-- Admin Messaging Inner Container -->
        <div class="admin-messaging-container">
            <!-- Sidebar: Conversation List -->
            <aside class="conversations-sidebar">
                <!-- Removed Title "Messagerie" as requested -->
                <div class="conversations-list">
                    <?php foreach ($conversations as $conv): ?>
                        <?php
                        $isActive = $activeConversation && $conv->getId() === $activeConversation->getId();
                        ?>
                        <a href="index.php?action=adminMessages&id=<?= $conv->getId() ?>"
                            class="conversation-item <?= $isActive ? 'active' : '' ?>">
                            <div class="conv-avatar">
                                <?php if (!empty($conv->getUserImage())): ?>
                                    <img src="<?= $conv->getUserImage() ?>" alt="Avatar" class="avatar-circle">
                                <?php else: ?>
                                    <div class="avatar-circle">
                                        <?= strtoupper(substr($conv->getUserName(), 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="conv-info">
                                <span class="conv-name"><?= htmlspecialchars($conv->getUserName()) ?></span>
                                <span class="conv-preview">
                                    <?= $conv->getLastMessageDate() ? date('d/m H:i', strtotime($conv->getLastMessageDate())) : 'Nouvelle conversation' ?>
                                </span>
                                <p class="conv-excerpt">
                                    <?php
                                    $lastMsg = $conv->getLastMessageContent();
                                    if ($lastMsg) {
                                        // Quick check if it's a quote request (JSON)
                                        if (
                                            strpos($lastMsg, '"type":"quote_request"') !== false ||
                                            (strpos($lastMsg, '"items":[') !== false && strpos($lastMsg, '{') === 0)
                                        ) {
                                            echo "Demande de devis...";
                                        } else {
                                            // Decode if simple text but stored as json? No, standard is text.
                                            // Clean tags just in case
                                            $clean = strip_tags($lastMsg);
                                            echo (strlen($clean) > 30) ? substr($clean, 0, 30) . '...' : $clean;
                                        }
                                    } else {
                                        echo "Cliquez pour voir les messages...";
                                    }
                                    ?>
                                </p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </aside>

            <!-- Main: Chat Area -->
            <main class="admin-chat-area">
                <?php if ($activeConversation): ?>
                    <div class="chat-header admin-header">
                        <div class="user-info">
                            <?php if (!empty($activeConversation->getUserImage())): ?>
                                <img src="<?= $activeConversation->getUserImage() ?>" alt="Avatar" class="avatar-circle small">
                            <?php else: ?>
                                <div class="avatar-circle small">
                                    <?= strtoupper(substr($activeConversation->getUserName(), 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <h3><?= htmlspecialchars($activeConversation->getUserName()) ?></h3>
                        </div>
                    </div>

                    <div class="chat-messages" id="chat-messages">
                        <?php if (empty($messages)): ?>
                            <p class="no-messages">Aucun message dans cette conversation.</p>
                        <?php else: ?>
                            <?php foreach ($messages as $msg): ?>
                                <?php
                                $isSentByMe = $msg->getSenderId() === $user->getId();
                                ?>
                                <div class="message-bubble <?= $isSentByMe ? 'message-sent' : 'message-received' ?>">
                                    <div class="message-content">
                                        <?php if ($msg->getType() === 'quote_request'): ?>
                                            <?php
                                            $data = json_decode($msg->getContent(), true);
                                            if ($data):
                                                ?>
                                                <div class="quote-request-card">
                                                    <div class="quote-card-header">
                                                        <strong>Demande de devis</strong>
                                                    </div>
                                                    <div class="quote-card-items">
                                                        <?php foreach ($data['items'] as $item): ?>
                                                            <div class="quote-card-item">
                                                                <img src="<?= htmlspecialchars($item['image']) ?>" alt=""
                                                                    class="quote-item-img">
                                                                <div class="quote-item-details">
                                                                    <span class="quote-item-name"><?= htmlspecialchars($item['name']) ?></span>
                                                                    <span class="quote-item-price"><?= number_format($item['price'], 2) ?>
                                                                        €</span>
                                                                    <span class="quote-item-qty">Quantité : <?= $item['quantity'] ?></span>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <div class="quote-card-message">
                                                        <?= nl2br(htmlspecialchars($data['user_message'])) ?>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <?= nl2br(htmlspecialchars($msg->getContent())) ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?= nl2br(htmlspecialchars($msg->getContent())) ?>
                                        <?php endif; ?>
                                        <span class="message-time">
                                            <?= date('d/m/Y H:i', strtotime($msg->getCreatedAt())) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="chat-input-area">
                        <form action="index.php?action=sendMessage" method="POST" class="message-form">
                            <input type="hidden" name="conversation_id" value="<?= $activeConversation->getId() ?>">
                            <textarea name="content" placeholder="Répondre..." required></textarea>
                            <button type="submit" class="btn btn-dark">Envoyer</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Sélectionnez une conversation pour commencer.</p>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<script>
    const chatContainer = document.getElementById('chat-messages');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>