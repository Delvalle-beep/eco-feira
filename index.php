<?php
session_start();

// Inicializa o carrinho se não existir
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Dados dos produtos
$products = [
    [
        'id' => '1',
        'name' => 'Cesta de Verduras da Estação',
        'price' => 35.00,
        'unit' => 'unidade',
        'category' => 'Verduras',
        'image' => 'https://picsum.photos/400/300?random=1',
        'seller_name' => 'Sítio Sol Nascente',
        'seller_location' => 'Atibaia, SP',
        'seller_rating' => 4.8,
        'description' => 'Seleção fresca colhida hoje: alface americana, rúcula, espinafre e couve manteiga. Tudo orgânico certificado.'
    ],
    [
        'id' => '2',
        'name' => 'Tomate Italiano Orgânico',
        'price' => 12.50,
        'unit' => 'kg',
        'category' => 'Legumes',
        'image' => 'https://picsum.photos/400/300?random=2',
        'seller_name' => 'Horta da Dona Maria',
        'seller_location' => 'Ibiúna, SP',
        'seller_rating' => 4.9,
        'description' => 'Tomates vermelhos e suculentos, perfeitos para molhos. Cultivados sem agrotóxicos.'
    ],
    [
        'id' => '3',
        'name' => 'Mel Silvestre Puro',
        'price' => 28.00,
        'unit' => 'pote 500g',
        'category' => 'Mercearia',
        'image' => 'https://picsum.photos/400/300?random=3',
        'seller_name' => 'Apiário Doce Vida',
        'seller_location' => 'Botucatu, SP',
        'seller_rating' => 5.0,
        'description' => 'Mel cru, não pasteurizado. Notas florais intensas da flora nativa da Cuesta.'
    ],
    [
        'id' => '4',
        'name' => 'Queijo Minas Artesanal',
        'price' => 45.00,
        'unit' => 'peça',
        'category' => 'Laticínios',
        'image' => 'https://picsum.photos/400/300?random=4',
        'seller_name' => 'Fazenda Santa Rita',
        'seller_location' => 'Serra da Canastra, MG',
        'seller_rating' => 4.7,
        'description' => 'Produzido com leite cru de vacas felizes criadas a pasto. Cura de 21 dias.'
    ],
    [
        'id' => '5',
        'name' => 'Bananas Prata',
        'price' => 8.90,
        'unit' => 'dúzia',
        'category' => 'Frutas',
        'image' => 'https://picsum.photos/400/300?random=5',
        'seller_name' => 'Sítio Sol Nascente',
        'seller_location' => 'Atibaia, SP',
        'seller_rating' => 4.8,
        'description' => 'Docinhas e no ponto certo. Cultivo consorciado com cacau.'
    ],
    [
        'id' => '6',
        'name' => 'Ovos Caipiras',
        'price' => 18.00,
        'unit' => 'dúzia',
        'category' => 'Laticínios',
        'image' => 'https://picsum.photos/400/300?random=6',
        'seller_name' => 'Horta da Dona Maria',
        'seller_location' => 'Ibiúna, SP',
        'seller_rating' => 4.9,
        'description' => 'Ovos de galinhas livres de gaiolas, alimentadas apenas com milho orgânico e verduras.'
    ],
    [
        'id' => '7',
        'name' => 'Pão de Fermentação Natural',
        'price' => 22.00,
        'unit' => 'unidade',
        'category' => 'Mercearia',
        'image' => 'https://picsum.photos/400/300?random=7',
        'seller_name' => 'Padaria do Grão',
        'seller_location' => 'São Paulo, SP',
        'seller_rating' => 4.6,
        'description' => 'Feito com farinha orgânica integral e levain de 5 anos.'
    ],
    [
        'id' => '8',
        'name' => 'Morangos Selecionados',
        'price' => 15.00,
        'unit' => 'bandeja',
        'category' => 'Frutas',
        'image' => 'https://picsum.photos/400/300?random=8',
        'seller_name' => 'Sítio Sol Nascente',
        'seller_location' => 'Atibaia, SP',
        'seller_rating' => 4.8,
        'description' => 'Morangos doces e pequenos, característicos do cultivo orgânico real.'
    ]
];

// Processa ações do carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' && isset($_POST['product_id'])) {
            $productId = $_POST['product_id'];
            $product = array_filter($products, fn($p) => $p['id'] === $productId);
            $product = reset($product);
            
            if ($product) {
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity']++;
                } else {
                    $_SESSION['cart'][$productId] = [
                        'product' => $product,
                        'quantity' => 1
                    ];
                }
            }
        } elseif ($_POST['action'] === 'remove' && isset($_POST['product_id'])) {
            unset($_SESSION['cart'][$_POST['product_id']]);
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . (isset($_GET['category']) ? '?category=' . $_GET['category'] : ''));
    exit;
}

// Extrai categorias únicas
$categories = array_unique(array_column($products, 'category'));
array_unshift($categories, 'Todos');

// Filtra produtos por categoria
$selectedCategory = $_GET['category'] ?? 'Todos';
$filteredProducts = $selectedCategory === 'Todos' 
    ? $products 
    : array_filter($products, fn($p) => $p['category'] === $selectedCategory);

// Calcula total do carrinho
$cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
$cartTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $cartTotal += $item['product']['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoFeira - Produtos Orgânicos Frescos</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <i class="fas fa-leaf"></i>
                <span>EcoFeira</span>
            </div>
            <nav class="header-nav">
                <a href="#produtos">Produtos</a>
                <a href="#sobre">Sobre</a>
                <a href="#contato">Contato</a>
                <a href="login.php" class="btn-admin">
                    <i class="fas fa-user-shield"></i>
                    Admin
                </a>
            </nav>
            <div class="header-actions">
                <button class="btn-ai" onclick="openAIModal()">
                    <i class="fas fa-hat-wizard"></i>
                    Chef IA
                </button>
                <button class="btn-cart" onclick="toggleCart()">
                    <i class="fas fa-shopping-basket"></i>
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Do Campo à Mesa</h1>
        <p>Produtos orgânicos frescos, direto dos pequenos produtores locais para sua casa.</p>
        <a href="#produtos" class="btn-hero">
            Ver Produtos
            <i class="fas fa-arrow-down"></i>
        </a>
    </section>

    <!-- Categories Filter -->
    <section class="categories" id="produtos">
        <div class="categories-container">
            <?php foreach ($categories as $category): ?>
                <a href="?category=<?php echo urlencode($category); ?>#produtos" 
                   class="category-btn <?php echo $selectedCategory === $category ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($category); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Products Grid -->
    <main class="products-section">
        <div class="products-grid">
            <?php foreach ($filteredProducts as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-seller">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($product['seller_name']); ?>
                        </div>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="product-footer">
                            <div class="product-price">
                                <span class="price-label">Preço por <?php echo htmlspecialchars($product['unit']); ?></span>
                                <span class="price-value">R$ <?php echo number_format($product['price'], 2, ',', '.'); ?></span>
                            </div>
                            <form method="post" class="add-form">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="action" value="add" class="btn-add">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- Seção Sobre a EcoFeira -->
    <section class="about-section" id="sobre">
        <div class="about-container">
            <h2>Sobre a EcoFeira</h2>
            <div class="about-content">
                <div class="about-card">
                    <i class="fas fa-bullseye"></i>
                    <h3>Nossa Missão</h3>
                    <p>Conectar produtores locais de alimentos orgânicos diretamente aos consumidores, promovendo saúde, sustentabilidade e economia justa.</p>
                </div>
                <div class="about-card">
                    <i class="fas fa-eye"></i>
                    <h3>Nossa Visão</h3>
                    <p>Ser a principal plataforma de comércio de produtos orgânicos no Brasil, transformando a forma como as pessoas consomem alimentos saudáveis.</p>
                </div>
                <div class="about-card">
                    <i class="fas fa-heart"></i>
                    <h3>Nossos Valores</h3>
                    <p>Sustentabilidade, transparência, qualidade, apoio aos pequenos produtores e compromisso com a saúde e bem-estar.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Contato -->
    <section class="contact-section" id="contato">
        <div class="contact-container">
            <div class="contact-header">
                <h2>Entre em Contato</h2>
                <p>Tem dúvidas sobre nossos produtos? Quer fazer um pedido especial? Estamos aqui para ajudar!</p>
            </div>
            
            <div class="contact-content">
                <!-- Informações de contato -->
                <div class="contact-info">
                    <div class="info-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>Endereço</h3>
                        <p>Rua das Hortaliças, 123<br>Osasco - SP, 06000-000</p>
                    </div>
                    
                    <div class="info-card">
                        <i class="fas fa-phone"></i>
                        <h3>Telefone</h3>
                        <p>(11) 98765-4321<br>(11) 3555-1234</p>
                    </div>
                    
                    <div class="info-card">
                        <i class="fas fa-envelope"></i>
                        <h3>E-mail</h3>
                        <p>contato@ecofeira.com.br<br>vendas@ecofeira.com.br</p>
                    </div>
                    
                    <div class="info-card">
                        <i class="fas fa-clock"></i>
                        <h3>Horário</h3>
                        <p>Seg a Sex: 8h - 18h<br>Sábados: 8h - 14h</p>
                    </div>
                    
                    <div class="info-card social-card">
                        <i class="fas fa-share-alt"></i>
                        <h3>Redes Sociais</h3>
                        <div class="social-links">
                            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
                            <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Formulário de contato -->
                <div class="contact-form-wrapper">
                    <h3>Envie sua Mensagem</h3>
                    <form method="POST" action="contato.php" class="contact-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nome">
                                    <i class="fas fa-user"></i>
                                    Nome Completo *
                                </label>
                                <input type="text" 
                                       id="nome" 
                                       name="nome" 
                                       placeholder="Seu nome completo"
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i>
                                    E-mail *
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       placeholder="seu@email.com"
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefone">
                                <i class="fas fa-phone"></i>
                                Telefone *
                            </label>
                            <input type="tel" 
                                   id="telefone" 
                                   name="telefone" 
                                   placeholder="(11) 98765-4321"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="mensagem">
                                <i class="fas fa-comment"></i>
                                Mensagem *
                            </label>
                            <textarea id="mensagem" 
                                      name="mensagem" 
                                      rows="6" 
                                      placeholder="Digite sua mensagem aqui..."
                                      required></textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <p>&copy; 2024 EcoFeira - Todos os direitos reservados</p>
            <p>Projeto Integrador - IFSP Osasco</p>
        </div>
    </footer>

    <!-- Cart Drawer -->
    <div class="cart-overlay" id="cartOverlay" onclick="toggleCart()"></div>
    <aside class="cart-drawer" id="cartDrawer">
        <div class="cart-header">
            <h2>Seu Carrinho</h2>
            <button class="btn-close" onclick="toggleCart()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="cart-items">
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="cart-empty">
                    <i class="fas fa-shopping-basket"></i>
                    <p>Seu carrinho está vazio.</p>
                    <button class="btn-primary" onclick="toggleCart()">Começar a comprar</button>
                </div>
            <?php else: ?>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="cart-item">
                        <img src="<?php echo $item['product']['image']; ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>">
                        <div class="cart-item-info">
                            <h4><?php echo htmlspecialchars($item['product']['name']); ?></h4>
                            <span class="cart-item-seller"><?php echo htmlspecialchars($item['product']['seller_name']); ?></span>
                            <span class="cart-item-price">
                                <?php echo $item['quantity']; ?>x R$ <?php echo number_format($item['product']['price'], 2, ',', '.'); ?>
                            </span>
                        </div>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
                            <button type="submit" name="action" value="remove" class="btn-remove">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="cart-footer">
                <div class="cart-total">
                    <span>Total Estimado</span>
                    <span class="total-value">R$ <?php echo number_format($cartTotal, 2, ',', '.'); ?></span>
                </div>
                <button class="btn-checkout">
                    Finalizar Pedido
                    <i class="fas fa-arrow-right"></i>
                </button>
                <p class="cart-note">Você será conectado diretamente com os produtores via WhatsApp.</p>
            </div>
        <?php endif; ?>
    </aside>

    <!-- AI Chef Modal -->
    <div class="modal-overlay" id="aiModalOverlay" onclick="closeAIModal()"></div>
    <div class="ai-modal" id="aiModal">
        <div class="ai-modal-header">
            <div class="ai-modal-title">
                <i class="fas fa-hat-wizard"></i>
                <div>
                    <h3>Chef Eco Inteligente</h3>
                    <span>Powered by AI</span>
                </div>
            </div>
            <button class="btn-close" onclick="closeAIModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="ai-chat-area" id="chatArea">
            <div class="ai-message model">
                <i class="fas fa-robot"></i>
                <p>Olá! Sou o Chef Orgânico. Posso sugerir receitas com os itens do seu carrinho ou tirar dúvidas sobre orgânicos. Como posso ajudar?</p>
            </div>
        </div>
        <div class="ai-input-area">
            <input type="text" id="aiInput" placeholder="Ex: Tenho batata doce, o que posso fazer?">
            <button class="btn-send" onclick="sendAIMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
        <p class="ai-disclaimer">A IA pode cometer erros. Verifique as informações importantes.</p>
    </div>

    <script>
        // Toggle Cart
        function toggleCart() {
            document.getElementById('cartDrawer').classList.toggle('open');
            document.getElementById('cartOverlay').classList.toggle('open');
        }

        // AI Modal
        function openAIModal() {
            document.getElementById('aiModal').classList.add('open');
            document.getElementById('aiModalOverlay').classList.add('open');
        }

        function closeAIModal() {
            document.getElementById('aiModal').classList.remove('open');
            document.getElementById('aiModalOverlay').classList.remove('open');
        }

        // AI Chat (simulado - pode integrar com API real)
        function sendAIMessage() {
            const input = document.getElementById('aiInput');
            const chatArea = document.getElementById('chatArea');
            const message = input.value.trim();
            
            if (!message) return;
            
            // Adiciona mensagem do usuário
            chatArea.innerHTML += `
                <div class="ai-message user">
                    <p>${message}</p>
                </div>
            `;
            
            input.value = '';
            
            // Simula resposta da IA
            setTimeout(() => {
                chatArea.innerHTML += `
                    <div class="ai-message model">
                        <i class="fas fa-robot"></i>
                        <p>Para integrar com uma IA real, você precisará configurar uma API backend em PHP que se comunique com serviços como OpenAI ou Google Gemini.</p>
                    </div>
                `;
                chatArea.scrollTop = chatArea.scrollHeight;
            }, 1000);
        }

        // Enter para enviar
        document.getElementById('aiInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendAIMessage();
            }
        });

        // Smooth scroll para âncoras
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>