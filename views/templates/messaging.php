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
                <img src="<?= $avatar ?>" alt="Avatar" class="avatar cursor-pointer"
                    onclick="document.getElementById('avatar-messaging-input').click();">
                <form action="index.php?action=updateAccount" method="post" enctype="multipart/form-data"
                    class="display-none">
                    <input type="hidden" name="redirect_to" value="messagerie">
                    <input type="file" name="avatar" id="avatar-messaging-input" onchange="this.form.submit()" aria-label="Changer de photo de profil">
                </form>
            </div>
            <span class="user-name"><?= htmlspecialchars($user->getFirstname() . ' ' . $user->getLastname()) ?></span>
        </div>

        <!-- Navigation -->
        <nav class="account-nav">
            <a href="index.php?action=compte" class="account-nav-item">
                <img src="img/icone-compte.png" alt="Infos">
                Informations
            </a>
            <a href="index.php?action=messagerie" class="account-nav-item active">
                <img src="img/icone-messagerie-active.png" alt="Messagerie">
                Messagerie
            </a>
            <a href="index.php?action=disconnectUser" class="account-nav-item">
                <img src="img/deconnexion.png" alt="Déconnexion">
                Déconnexion
            </a>
        </nav>
    </div>

    <main class="account-content messaging-content">
        <div class="chat-header">
            <div class="chat-title">
                <img src="img/avatar-resineo.png" alt="Resineo" class="chat-logo">
                <h2>Support client</h2>
            </div>
        </div>

        <div class="chat-messages" id="chat-messages">
            <?php if (empty($messages)): ?>
                <p class="empty-state">Commencez la discussion avec notre support.</p>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <?php $isWide = in_array($msg->getType(), ['quote_request', 'offer']); ?>
                    <div
                        class="message-bubble <?= $msg->getSenderId() === $user->getId() ? 'message-sent' : 'message-received' ?> <?= $isWide ? 'wide-bubble' : '' ?>">
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
                                            <?php
                                            $total = 0;
                                            foreach ($data['items'] as $item):
                                                $total += $item['price'] * $item['quantity'];
                                                ?>
                                                <div class="quote-card-item">
                                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="" class="quote-item-img">
                                                    <div class="quote-item-details">
                                                        <span class="quote-item-name"><?= htmlspecialchars($item['name']) ?></span>
                                                        <span class="quote-item-price"><?= number_format($item['price'], 2) ?> €</span>
                                                        <span class="quote-item-qty">Quantité : <?= $item['quantity'] ?></span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="quote-card-total">
                                            Total : <?= number_format($total, 2) ?> €
                                        </div>
                                        <div class="quote-card-message">
                                            <?= nl2br(htmlspecialchars($data['user_message'])) ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?= nl2br(htmlspecialchars($msg->getContent())) ?>
                                <?php endif; ?>
                            <?php elseif ($msg->getType() === 'offer'): ?>
                                <?php
                                $data = json_decode($msg->getContent(), true);
                                if ($data):
                                    ?>
                                    <div class="quote-request-card offer-card">
                                        <div class="quote-card-header">
                                            <strong>Offre</strong>
                                        </div>
                                        <div class="quote-card-items">
                                            <?php
                                            $total = 0;
                                            foreach ($data['items'] as $item):
                                                $total += $item['price'] * $item['quantity'];
                                                ?>
                                                <div class="quote-card-item">
                                                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="" class="quote-item-img">
                                                    <div class="quote-item-details">
                                                        <span class="quote-item-name"><?= htmlspecialchars($item['name']) ?></span>
                                                        <span class="quote-item-price"><?= number_format($item['price'], 2) ?> €</span>
                                                        <span class="quote-item-qty">Quantité : <?= $item['quantity'] ?></span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="quote-card-total">
                                            Total : <?= number_format($total, 2) ?> €
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
                <input type="hidden" name="conversation_id" value="<?= $conversation->getId() ?>">
                <textarea name="content" placeholder="Entrez votre message ici..." aria-label="Votre message"
                    required><?= isset($prefillContent) ? htmlspecialchars($prefillContent) : '' ?></textarea>
                <button type="submit" class="btn btn-dark">Envoyer</button>
            </form>
        </div>
    </main>
</div>

<script>
    const chatContainer = document.getElementById('chat-messages');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>