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
        <!-- Bloc résumé utilisateur -->
        <div class="user-summary">
            <div class="user-info">
                <?php $avatar = $user->getImage() ? $user->getImage() : "img/avatar-default.png"; ?>
                <img src="<?= $avatar ?>" alt="Avatar" class="avatar cursor-pointer"
                    onclick="document.getElementById('avatar-admin-input').click();">
                <form action="index.php?action=updateAccount" method="post" enctype="multipart/form-data"
                    class="display-none">
                    <input type="hidden" name="redirect_to" value="adminMessages">
                    <input type="file" name="avatar" id="avatar-admin-input" onchange="this.form.submit()"
                        aria-label="Changer de photo de profil">
                </form>
            </div>
            <span class="user-name"><?= htmlspecialchars($user->getFirstname() . ' ' . $user->getLastname()) ?></span>
        </div>

        <!-- Navigation du compte -->
        <nav class="account-nav">
            <a href="index.php?action=compte" class="account-nav-item">
                <img src="img/icone-compte.png" alt="Infos">
                Informations
            </a>
            <a href="index.php?action=adminMessages" class="account-nav-item active">
                <img src="img/icone-messagerie-active.png" alt="Messagerie">
                Messagerie
            </a>
            <a href="index.php?action=disconnectUser" class="account-nav-item">
                <img src="img/deconnexion.png" alt="Déconnexion">
                Déconnexion
            </a>
        </nav>
    </div>

    <div class="account-content">
        <!-- Conteneur messagerie admin -->
        <div class="admin-messaging-container">
            <!-- Barre latérale : Liste des conversations -->
            <aside class="conversations-sidebar">
                <div class="conversations-list">
                    <?php foreach ($conversations as $conv): ?>
                        <?php
                        $isActive = $activeConversation && $conv->getId() === $activeConversation->getId();
                        ?>
                        <a href="index.php?action=adminMessages&id=<?= $conv->getId() ?>"
                            class="conversation-item <?= $isActive ? 'active' : '' ?>">
                            <div class="conv-avatar">
                                <!-- Affichage de l'avatar -->
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
                                    <!-- Date du dernier message -->
                                    <?= $conv->getLastMessageDate() ? date('d/m H:i', strtotime($conv->getLastMessageDate())) : 'Nouvelle conversation' ?>
                                </span>
                                <p class="conv-excerpt">
                                    <?php
                                    $lastMsg = $conv->getLastMessageContent();
                                    if ($lastMsg) {
                                        // Gestion de l'affichage : JSON (Devis) vs Texte brut
                                        if (
                                            strpos($lastMsg, '"type":"quote_request"') !== false ||
                                            (strpos($lastMsg, '"items":[') !== false && strpos($lastMsg, '{') === 0)
                                        ) {
                                            // Si c'est un JSON technique, on affiche un libellé simple
                                            echo "Demande de devis...";
                                        } else {
                                            // Si c'est du texte, on nettoie et on tronque si nécessaire
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

            <!-- Zone de discussion principale -->
            <div class="admin-chat-area">
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
                                $isWide = in_array($msg->getType(), ['quote_request', 'offer']);
                                ?>
                                <div
                                    class="message-bubble <?= $isSentByMe ? 'message-sent' : 'message-received' ?> <?= $isWide ? 'wide-bubble' : '' ?>">
                                    <div class="message-content">
                                        <?php if ($msg->getType() === 'quote_request'): ?>
                                            <?php
                                            $data = json_decode($msg->getContent(), true);
                                            if ($data && isset($data['items'])):
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
                                                    <div class="quote-card-total">
                                                        Total : <?= number_format($total, 2) ?> €
                                                    </div>
                                                    <div class="quote-card-message">
                                                        <?= nl2br(htmlspecialchars($data['user_message'])) ?>
                                                    </div>
                                                    <div class="quote-card-actions">
                                                        <button type="button" class="btn btn-dark"
                                                            onclick='openOfferForm(<?= htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') ?>)'>
                                                            Faire une offre
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <?= nl2br(htmlspecialchars($msg->getContent())) ?>
                                            <?php endif; ?>

                                        <?php elseif ($msg->getType() === 'offer'): ?>
                                            <?php
                                            $data = json_decode($msg->getContent(), true);
                                            if ($data && isset($data['items'])):
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
                        <!-- Formulaire d'offre (caché par défaut) -->
                        <div id="offer-form-container" class="offer-form-container display-none">
                            <div class="offer-form-header">
                                <h3>Faire une offre</h3>
                                <button type="button" class="close-offer-btn" onclick="closeOfferForm()">×</button>
                            </div>
                            <div class="offer-items-list" id="offer-items-list">
                                <!-- Les éléments seront injectés ici via JS -->
                            </div>
                            <div id="offer-total">Total : 0.00
                                €</div>

                            <!-- Champ de recherche de produit -->
                            <div class="search-products-container">
                                <input type="text" id="product-search-input"
                                    placeholder="Ajouter un produit (rechercher...)"
                                    aria-label="Rechercher un produit à ajouter">
                                <div id="search-results"></div>
                            </div>
                            <div class="offer-form-footer">
                                <textarea id="offer-message" placeholder="Réponse..." class="offer-message-input"
                                    aria-label="Message de l'offre"></textarea>
                                <button type="button" class="btn btn-dark" onclick="submitOffer()">Envoyer</button>
                            </div>
                        </div>

                        <form action="index.php?action=sendMessage" method="POST" class="message-form"
                            id="main-message-form">
                            <input type="hidden" name="conversation_id" value="<?= $activeConversation->getId() ?>">
                            <input type="hidden" name="type" id="message-type" value="text">
                            <!-- Support des types de messages -->
                            <textarea name="content" id="message-content" placeholder="Répondre..." required
                                aria-label="Votre message"></textarea>
                            <button type="submit" class="btn btn-dark">Envoyer</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>Aucune conversation</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Faire une offre -->
<template id="offer-item-template">
    <div class="offer-item-row">
        <img src="" alt="" class="offer-item-img">
        <div class="offer-item-details">
            <span class="offer-item-name-text"></span>
            <input type="hidden" class="offer-item-name" value="">
            <div class="offer-item-controls">
                <div class="control-group">
                    <span class="control-label">Prix (€)</span>
                    <input type="number" class="offer-item-price" step="0.01" value="" placeholder=""
                        oninput="calculateOfferTotal()">
                </div>
                <div class="control-group">
                    <span class="control-label">Qté</span>
                    <div class="offer-qty-control">
                        <button type="button" class="qty-btn" onclick="updateOfferQty(this, -1)">-</button>
                        <input type="number" class="offer-item-qty offer-qty-input" step="1" value="" readonly>
                        <button type="button" class="qty-btn" onclick="updateOfferQty(this, 1)">+</button>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="remove-item-btn" onclick="removeOfferItem(this)">×</button>
    </div>
</template>



<script>
    // Scroll automatique vers le bas de la zone de chat
    const chatContainer = document.getElementById('chat-messages');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Calculer le total de l'offre en temps réel
    function calculateOfferTotal() {
        let total = 0;
        document.querySelectorAll('.offer-item-row').forEach(row => {
            const price = parseFloat(row.querySelector('.offer-item-price').value) || 0;
            const qty = parseInt(row.querySelector('.offer-item-qty').value) || 0;
            total += price * qty;
        });
        const totalDiv = document.getElementById('offer-total');
        if (totalDiv) {
            totalDiv.textContent = 'Total : ' + total.toFixed(2) + ' €';
        }
    }

    // Ouvrir le formulaire d'offre (pré-rempli avec les données du devis)
    function openOfferForm(quoteData) {
        const container = document.getElementById('offer-form-container');
        const list = document.getElementById('offer-items-list');
        const template = document.getElementById('offer-item-template');
        list.innerHTML = '';

        // Remplir les items à partir du devis
        if (quoteData && quoteData.items) {
            quoteData.items.forEach(item => {
                addItemToForm(item.name, item.price, item.quantity, item.image);
            });
        }

        container.style.display = 'block';
        document.getElementById('main-message-form').style.display = 'none';
        chatContainer.scrollTop = chatContainer.scrollHeight; // Scroll pour voir le formulaire
        calculateOfferTotal();
    }

    // Fermer le formulaire d'offre
    function closeOfferForm() {
        document.getElementById('offer-form-container').style.display = 'none';
        document.getElementById('main-message-form').style.display = 'flex';
    }

    // Ajouter un produit à la liste de l'offre
    function addItemToForm(name, price, qty, image) {
        const list = document.getElementById('offer-items-list');
        const template = document.getElementById('offer-item-template');
        const clone = template.content.cloneNode(true);

        clone.querySelector('.offer-item-img').src = image || 'img/camera-icon.png';
        clone.querySelector('.offer-item-name-text').textContent = name;
        clone.querySelector('.offer-item-name').value = name;
        clone.querySelector('.offer-item-price').value = price;
        clone.querySelector('.offer-item-qty').value = qty;

        list.appendChild(clone);
        calculateOfferTotal();
    }

    // Supprimer un produit de l'offre
    function removeOfferItem(btn) {
        btn.closest('.offer-item-row').remove();
        calculateOfferTotal();
    }

    // Mettre à jour la quantité (+/-)
    function updateOfferQty(btn, delta) {
        const wrapper = btn.closest('.offer-qty-control');
        const input = wrapper.querySelector('.offer-qty-input');
        let val = parseInt(input.value) || 0;
        val += delta;
        if (val < 1) val = 1;
        input.value = val;
        calculateOfferTotal();
    }

    // Logique de recherche de produits
    const searchInput = document.getElementById('product-search-input');
    const searchResults = document.getElementById('search-results');
    let debounceTimer;

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const term = this.value.trim();

            if (term.length === 0) {
                searchResults.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch('index.php?action=searchJson&term=' + encodeURIComponent(term))
                    .then(response => response.json())
                    .then(data => {
                        searchResults.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(product => {
                                const div = document.createElement('div');
                                div.className = 'search-result-item';
                                div.innerHTML = `
                                    <img src="${product.image}" class="search-result-img">
                                    <div class="search-item-info">
                                        <div class="search-item-name">${product.name}</div>
                                        <div class="search-item-sub">${product.price} €</div>
                                    </div>
                                `;
                                div.onclick = () => {
                                    addItemToForm(product.name, product.price, 1, product.image);
                                    searchInput.value = '';
                                    searchResults.style.display = 'none';
                                };
                                searchResults.appendChild(div);
                            });
                            searchResults.style.display = 'block';
                        } else {
                            searchResults.style.display = 'none';
                        }
                    })
                    .catch(err => {
                        console.error('Search error:', err);
                    });
            }, 100);
        });

        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }

    // Soumettre l'offre (construit le JSON et l'envoie)
    function submitOffer() {
        const items = [];
        document.querySelectorAll('.offer-item-row').forEach(row => {
            const name = row.querySelector('.offer-item-name').value;
            const price = parseFloat(row.querySelector('.offer-item-price').value);
            const quantity = parseInt(row.querySelector('.offer-item-qty').value);
            const image = row.querySelector('.offer-item-img').getAttribute('src');

            if (name && quantity > 0) {
                items.push({ name, price, quantity, image });
            }
        });

        const message = document.getElementById('offer-message').value;

        const offerData = {
            type: 'offer',
            items: items,
            user_message: message
        };

        const contentInput = document.getElementById('message-content');
        const typeInput = document.getElementById('message-type');

        contentInput.value = JSON.stringify(offerData);
        typeInput.value = 'offer';
        contentInput.removeAttribute('required');

        document.getElementById('main-message-form').submit();
    }
</script>