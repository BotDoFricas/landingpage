<?php
// index.php
require_once "config.php";

// Gera token CSRF para os formulários
$csrf_token = gerarTokenCsrf();

// Verifica se usuário já está logado
$logado = isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;
$nome_usuario = $_SESSION["usuario_nome"] ?? "";
?>
<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Impulsionamento de RH</title>

        <style>
            /* RESET */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: Arial, sans-serif;
            }

            /* FUNDO */
            body {
                background: linear-gradient(135deg, #050505 0%, #0d0d0d 25%, #0a0a0a 50%, #050505 75%, #0d0d0d 100%);
                background-size: 400% 400%;
                animation: gradientShift 15s ease infinite;
                color: #d8c27a;
                min-height: 100vh;
                position: relative;
                overflow-x: hidden;
            }

            /* Padrão geométrico de fundo */
            body::before {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image:
                    radial-gradient(circle at 20% 50%, rgba(216, 194, 122, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(216, 194, 122, 0.03) 0%, transparent 50%),
                    radial-gradient(circle at 40% 20%, rgba(216, 194, 122, 0.04) 0%, transparent 50%);
                pointer-events: none;
                z-index: 0;
            }

            body::after {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image:
                    repeating-linear-gradient(
                        45deg,
                        transparent,
                        transparent 2px,
                        rgba(216, 194, 122, 0.02) 2px,
                        rgba(216, 194, 122, 0.02) 4px
                    ),
                    repeating-linear-gradient(
                        -45deg,
                        transparent,
                        transparent 2px,
                        rgba(216, 194, 122, 0.02) 2px,
                        rgba(216, 194, 122, 0.02) 4px
                    );
                pointer-events: none;
                z-index: 0;
            }

            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            /* NAVBAR */
            .navbar {
                position: fixed;
                top: 0;
                width: 100%;
                background: rgba(5, 5, 5, 0.9);
                padding: 15px 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                z-index: 999;
                border-bottom: 1px solid rgba(216, 194, 122, 0.1);
            }

            .navbar .logo {
                font-weight: bold;
                color: #d8c27a;
                font-size: 1.2rem;
            }

            .navbar .user-info {
                display: flex;
                align-items: center;
                gap: 15px;
                color: #ccc;
                font-size: 14px;
            }

            .navbar .user-info a {
                color: #d8c27a;
                text-decoration: none;
                cursor: pointer;
            }

            .navbar .user-info a:hover {
                text-decoration: underline;
            }

            /* HERO */
            header {
                height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                position: relative;
                z-index: 1;
                background:
                    linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)),
                    url("https://images.unsplash.com/photo-1521737604893-d14cc237f11d")
                        center/cover no-repeat;
            }

            header h1 {
                font-size: 2.7rem;
                margin-bottom: 10px;
            }

            header p {
                color: #bdbdbd;
                max-width: 600px;
            }

            /* BOTÃO */
            .btn {
                margin-top: 20px;
                background: linear-gradient(145deg, #d8c27a, #b89b55);
                color: #0a0a0a;
                padding: 14px 28px;
                border: none;
                border-radius: 30px;
                font-weight: bold;
                cursor: pointer;
                transition: 0.3s;
                text-decoration: none;
                display: inline-block;
                font-size: 15px;
            }

            .btn:hover {
                transform: scale(1.05);
            }

            .btn-sm {
                padding: 8px 18px;
                font-size: 13px;
                margin: 5px;
            }

            /* SEÇÕES */
            .section {
                padding: 90px 20px;
                text-align: center;
                position: relative;
                z-index: 1;
            }

            main {
                position: relative;
                z-index: 1;
            }

            /* FEATURES */
            .features {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                gap: 25px;
                margin-top: 40px;
            }

            .row {
                display: flex;
                justify-content: center;
                gap: 20px;
                flex-wrap: wrap;
            }

            .row-1,
            .row-2,
            .row-3 {
                width: auto;
            }

            /* CARD */
            .card {
                background: rgba(20, 20, 20, 0.9);
                border: 1px solid rgba(216, 194, 122, 0.2);
                border-radius: 20px;
                padding: 20px;
                color: #fff;
                transition: transform 0.3s, border-color 0.3s, box-shadow 0.3s;
                width: 220px;
                cursor: pointer;
                position: relative;
                overflow: hidden;
            }

            .card:hover {
                transform: translateY(-10px);
                border-color: #d8c27a;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
            }

            .card img {
                width: 100%;
                height: 120px;
                object-fit: cover;
                border-radius: 12px;
                margin-bottom: 10px;
            }

            .card h3 {
                color: #d8c27a;
                margin-bottom: 10px;
            }

            .card-detail-btn {
                display: inline-block;
                margin-top: 10px;
                font-size: 0.9rem;
                color: #d8c27a;
                text-decoration: underline;
                cursor: pointer;
                transition: color 0.3s;
            }

            .card-detail-btn:hover {
                color: #fff;
            }

            /* MODAL PARA DETALHES DO CARD */
            .card-modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                justify-content: center;
                align-items: center;
                z-index: 2000;
                animation: fadeIn 0.3s ease-in;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .card-modal.show {
                display: flex;
            }

            .card-modal-content {
                background: linear-gradient(135deg, rgba(20, 20, 20, 0.95), rgba(30, 30, 30, 0.95));
                padding: 40px;
                border-radius: 20px;
                border: 2px solid rgba(216, 194, 122, 0.4);
                width: 90%;
                max-width: 600px;
                text-align: left;
                position: relative;
                animation: slideUp 0.4s ease-out;
                max-height: 80vh;
                overflow-y: auto;
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .card-modal-content::-webkit-scrollbar {
                display: none;
            }

            @keyframes slideUp {
                from {
                    transform: translateY(50px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .card-modal-content h2 {
                color: #d8c27a;
                margin-bottom: 15px;
                font-size: 2rem;
            }

            .card-modal-content img {
                width: 100%;
                height: 250px;
                object-fit: cover;
                border-radius: 15px;
                margin-bottom: 20px;
            }

            .card-modal-content p {
                color: #bdbdbd;
                margin-bottom: 12px;
                line-height: 1.6;
            }

            .close-modal {
                position: absolute;
                top: 15px;
                right: 20px;
                font-size: 2rem;
                cursor: pointer;
                color: #d8c27a;
                transition: 0.3s;
                background: none;
                border: none;
            }

            .close-modal:hover {
                color: #fff;
                transform: scale(1.2);
            }

            /* FOOTER */
            footer {
                background: #000;
                color: #d8c27a;
                text-align: center;
                padding: 20px;
                position: relative;
                z-index: 1;
            }

            /* POPUPS */
            .popup {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.85);
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }

            .popup.active {
                display: flex;
            }

            .popup-content {
                background: #111;
                padding: 35px;
                border-radius: 15px;
                width: 90%;
                max-width: 400px;
                text-align: center;
                border: 1px solid rgba(216, 194, 122, 0.2);
            }

            .popup-content h2 {
                color: #d8c27a;
                margin-bottom: 20px;
            }

            .popup-content input {
                width: 100%;
                padding: 12px;
                margin: 8px 0;
                border-radius: 8px;
                border: 1px solid rgba(216, 194, 122, 0.3);
                background: #000;
                color: #fff;
                font-size: 15px;
            }

            .popup-content input:focus {
                outline: none;
                border-color: #d8c27a;
            }

            .popup-content .mensagem {
                padding: 10px;
                margin: 10px 0;
                border-radius: 6px;
                font-size: 14px;
                display: none;
            }

            .popup-content .mensagem.erro {
                display: block;
                background: rgba(255, 0, 0, 0.15);
                color: #ff6b6b;
                border: 1px solid rgba(255, 0, 0, 0.3);
            }

            .popup-content .mensagem.sucesso {
                display: block;
                background: rgba(0, 255, 0, 0.1);
                color: #51cf66;
                border: 1px solid rgba(0, 255, 0, 0.3);
            }

            .popup-content .fechar {
                color: #888;
                cursor: pointer;
                float: right;
                font-size: 24px;
                transition: 0.3s;
            }

            .popup-content .fechar:hover {
                color: #d8c27a;
            }

            .popup-content .link {
                color: #888;
                font-size: 13px;
                margin-top: 15px;
            }

            .popup-content .link a {
                color: #d8c27a;
                cursor: pointer;
                text-decoration: none;
            }

            .popup-content .link a:hover {
                text-decoration: underline;
            }

            /* TEXTOBOX DIAMANTE 3D */
            .diamond-box {
                margin: 40px auto;
                max-width: 900px;
                padding: 40px;
                background: rgba(10, 10, 10, 0.6);
                border: 1px solid rgba(216, 194, 122, 0.25);
                border-radius: 20px;
                position: relative;
                overflow: hidden;
                backdrop-filter: blur(12px);
                transform: perspective(800px) rotateX(2deg);
            }

            .diamond-box::before {
                content: "";
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, transparent, rgba(216, 194, 122, 0.15), transparent);
                animation: shine 4s linear infinite;
            }

            @keyframes shine {
                0% { transform: rotate(0deg) translateX(-50%); }
                100% { transform: rotate(360deg) translateX(-50%); }
            }

            .diamond {
                position: absolute;
                width: 10px;
                height: 10px;
                background: #d8c27a;
                transform: rotate(45deg);
                opacity: 0.6;
                animation: float 3s infinite ease-in-out;
            }

            .diamond:nth-child(1) { top: 20px; left: 20px; }
            .diamond:nth-child(2) { top: 40px; right: 30px; animation-delay: 1s; }
            .diamond:nth-child(3) { bottom: 30px; left: 60px; animation-delay: 2s; }

            @keyframes float {
                0%, 100% { transform: rotate(45deg) translateY(0); }
                50% { transform: rotate(45deg) translateY(-8px); }
            }

            /* LOADING SPINNER */
            .spinner {
                display: none;
                width: 20px;
                height: 20px;
                border: 2px solid rgba(216, 194, 122, 0.3);
                border-top-color: #d8c27a;
                border-radius: 50%;
                animation: spin 0.6s linear infinite;
                margin: 10px auto;
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            /* ========================= */
            /* RESPONSIVE DESIGN */
            /* ========================= */

            @media (max-width: 1024px) {
                .card {
                    width: 200px;
                }

                .section {
                    padding: 60px 15px;
                }
            }

            @media (max-width: 768px) {
                header h1 {
                    font-size: 2rem;
                }

                header p {
                    font-size: 0.95rem;
                }

                .section {
                    padding: 50px 15px;
                }

                .features {
                    gap: 15px;
                }

                .row {
                    gap: 15px;
                }

                .card {
                    width: 180px;
                    padding: 15px;
                }

                .card img {
                    height: 100px;
                }

                .card h3 {
                    font-size: 1.1rem;
                }

                .card p {
                    font-size: 0.9rem;
                }

                .card-detail-btn {
                    font-size: 0.85rem;
                }

                .card-modal-content {
                    padding: 25px;
                    max-width: 90%;
                }

                .card-modal-content h2 {
                    font-size: 1.5rem;
                }

                .card-modal-content img {
                    height: 200px;
                    margin-bottom: 15px;
                }

                .card-modal-content p {
                    font-size: 0.9rem;
                    line-height: 1.5;
                }

                .close-modal {
                    font-size: 1.5rem;
                    top: 10px;
                    right: 15px;
                }

                .popup-content {
                    width: 95%;
                    max-width: 350px;
                    padding: 20px;
                }

                .popup-content h2 {
                    font-size: 1.5rem;
                    margin-bottom: 12px;
                }

                .btn {
                    padding: 12px 20px;
                    font-size: 0.95rem;
                }

                .diamond-box {
                    padding: 25px;
                    max-width: 95%;
                }

                .diamond-box h2 {
                    font-size: 1.8rem;
                }

                .diamond-box p {
                    font-size: 0.95rem;
                    line-height: 1.5;
                }
            }

            @media (max-width: 480px) {
                header {
                    height: 80vh;
                }

                header h1 {
                    font-size: 1.5rem;
                }

                header p {
                    font-size: 0.85rem;
                    max-width: 90%;
                }

                .section {
                    padding: 40px 12px;
                }

                .section h1,
                .section h2 {
                    font-size: 1.8rem;
                    margin-bottom: 20px;
                }

                .features {
                    flex-direction: column;
                    gap: 12px;
                }

                .row {
                    flex-direction: column;
                    align-items: center;
                    gap: 12px;
                }

                .card {
                    width: 100%;
                    max-width: 280px;
                    padding: 12px;
                }

                .card img {
                    height: 80px;
                }

                .card h3 {
                    font-size: 1rem;
                    margin-bottom: 8px;
                }

                .card p {
                    font-size: 0.85rem;
                    margin: 8px 0;
                }

                .card-detail-btn {
                    font-size: 0.8rem;
                    margin-top: 8px;
                }

                .card-modal-content {
                    padding: 20px;
                    max-width: 95%;
                    max-height: 85vh;
                    border-radius: 15px;
                }

                .card-modal-content h2 {
                    font-size: 1.3rem;
                    margin-bottom: 12px;
                }

                .card-modal-content img {
                    height: 150px;
                    margin-bottom: 12px;
                }

                .card-modal-content p {
                    font-size: 0.85rem;
                    line-height: 1.4;
                    margin-bottom: 10px;
                }

                .close-modal {
                    font-size: 1.3rem;
                    top: 8px;
                    right: 12px;
                }

                .popup-content {
                    width: 95%;
                    max-width: 300px;
                    padding: 18px;
                }

                .popup-content h2 {
                    font-size: 1.3rem;
                    margin-bottom: 12px;
                }

                .popup-content input {
                    padding: 10px;
                    font-size: 0.9rem;
                    margin: 8px 0;
                }

                .btn {
                    padding: 10px 20px;
                    font-size: 0.9rem;
                    margin-top: 12px;
                }

                .diamond-box {
                    padding: 20px;
                    max-width: 95%;
                    margin: 30px auto;
                }

                .diamond-box h2 {
                    font-size: 1.5rem;
                    margin-bottom: 15px;
                }

                .diamond-box p {
                    font-size: 0.9rem;
                    line-height: 1.5;
                    text-align: justify;
                }

                .diamond {
                    width: 8px;
                    height: 8px;
                }

                footer {
                    padding: 15px 10px;
                    font-size: 0.85rem;
                }
            }

            @media (max-width: 360px) {
                header h1 {
                    font-size: 1.3rem;
                }

                .section {
                    padding: 30px 10px;
                }

                .card {
                    max-width: 260px;
                }

                .card-modal-content {
                    padding: 15px;
                }

                .card-modal-content h2 {
                    font-size: 1.2rem;
                }
            }
        </style>
    </head>

    <body>
        <!-- NAVBAR -->
        <nav class="navbar">
            <div class="logo">✦ Impulsionamento RH</div>
            <div class="user-info">
                <?php if ($logado): ?>
                    <span>👤 <?= htmlspecialchars($nome_usuario) ?></span>
                    <a href="#" id="btnLogoutNav">Sair</a>
                <?php else: ?>
                    <a href="#" onclick="abrirLogin()">Entrar</a>
                    <a href="#" onclick="abrirCadastro()">Cadastrar</a>
                <?php endif; ?>
            </div>
        </nav>

        <header>
            <h1>Precisando de Ajuda com o seu pessoal do RH?</h1>
            <p>
                Com esse curso ajudaremos você a transformar sua empresa com
                gestão moderna.
            </p>
            <a href="#sobre" class="btn">Conheça nossa empresa</a>
        </header>

        <!-- SOBRE COM DIAMANTE 3D -->
        <section class="section" id="sobre">
            <div class="diamond-box">
                <span class="diamond"></span>
                <span class="diamond"></span>
                <span class="diamond"></span>
                <h2>Sobre a nossa empresa</h2>
                <p>
                    Somos uma empresa especializada em impulsionamento de RH, criada para conectar pessoas, talentos e oportunidades de forma estratégica e eficiente.
                </p>
                <p>
                    Nosso objetivo é transformar a gestão de recursos humanos em um diferencial competitivo para empresas que desejam crescer com equipes mais preparadas, motivadas e alinhadas aos seus valores.
                </p>
                <p>
                    Atuamos com foco em recrutamento inteligente, desenvolvimento profissional, fortalecimento da cultura organizacional e soluções inovadoras para gestão de pessoas.
                </p>
                <p>
                    Acreditamos que o sucesso de qualquer empresa começa pelas pessoas, e por isso trabalhamos para potencializar talentos e construir ambientes corporativos mais produtivos e humanos.
                </p>
                <p>
                    Nossa equipe reúne profissionais capacitados e comprometidos em oferecer atendimento personalizado, entendendo as necessidades de cada cliente para criar estratégias que realmente gerem resultados.
                </p>
                <p>
                    Mais do que prestar serviços, buscamos construir parcerias duradouras baseadas em confiança, inovação e crescimento conjunto.
                </p>
            </div>
        </section>

        <!-- PIRÂMIDE INVERTIDA -->
        <main>
            <section class="section">
                <h2>Por que escolher nossa solução?</h2>
                <div class="features">
                    <div class="row row-1">
                        <div class="card" onclick="abrirDetalhes(0)">
                            <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df" />
                            <h3>Gestão de Pessoas</h3>
                            <p></p>
                            <span class="card-detail-btn">Saiba mais →</span>
                        </div>
                        <div class="card" onclick="abrirDetalhes(1)">
                            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f" />
                            <h3>Resultados Rápidos</h3>
                            <p></p>
                            <span class="card-detail-btn">Saiba mais →</span>
                        </div>
                        <div class="card" onclick="abrirDetalhes(2)">
                            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d" />
                            <h3>Recrutamento Inteligente</h3>
                            <p></p>
                            <span class="card-detail-btn">Saiba mais →</span>
                        </div>
                    </div>
                    <div class="row row-2">
                        <div class="card" onclick="abrirDetalhes(3)">
                            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c" />
                            <h3>Simples</h3>
                            <p></p>
                            <span class="card-detail-btn">Saiba mais →</span>
                        </div>
                        <div class="card" onclick="abrirDetalhes(4)">
                            <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216" />
                            <h3>Seguro</h3>
                            <p></p>
                            <span class="card-detail-btn">Saiba mais →</span>
                        </div>
                    </div>
                    <div class="row row-3">
                        <div class="card" onclick="abrirDetalhes(5)">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978" />
                            <h3>Performance Máxima</h3>
                            <p></p>
                            <span class="card-detail-btn">Saiba mais →</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- LOGIN / CADASTRO -->
            <section class="section">
                <h2>Pronto para evoluir seu RH?</h2>
                <br />
                <?php if ($logado): ?>
                    <p style="color:#51cf66; margin-bottom:15px;">
                        ✅ Você está logado como <strong><?= htmlspecialchars(
                            $nome_usuario,
                        ) ?></strong>
                    </p>
                    <a href="dashboard.php" class="btn">Ir para Dashboard</a>
                <?php else: ?>
                    <button class="btn" onclick="abrirLogin()">Login</button>
                    <button class="btn" onclick="abrirCadastro()">Criar Conta</button>
                <?php endif; ?>
            </section>
        </main>

        <footer>
            <p>© 2026 - Impulsionamento de RH</p>
        </footer>

        <!-- CARD MODAL -->
        <div class="card-modal" id="cardModal">
            <div class="card-modal-content">
                <button class="close-modal" onclick="fecharDetalhes()">×</button>
                <img id="cardDetailImg" src="" />
                <h2 id="cardDetailTitle"></h2>
                <p id="cardDetailContent"></p>
            </div>
        </div>

        <!-- POPUP LOGIN -->
        <div class="popup" id="loginPopup">
            <div class="popup-content">
                <span class="fechar" onclick="fecharPopup('loginPopup')">&times;</span>
                <h2>Login</h2>
                <div class="mensagem" id="loginMsg"></div>
                <form id="loginForm" onsubmit="return fazerLogin(event)">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="email" name="email" placeholder="Seu email" required>
                    <input type="password" name="senha" placeholder="Sua senha" required minlength="6">
                    <div class="spinner" id="loginSpinner"></div>
                    <button type="submit" class="btn" id="loginBtn">Entrar</button>
                </form>
                <div class="link">
                    Não tem conta? <a onclick="fecharPopup('loginPopup'); abrirCadastro();">Cadastre-se</a>
                </div>
            </div>
        </div>

        <!-- POPUP CADASTRO -->
        <div class="popup" id="cadastroPopup">
            <div class="popup-content">
                <span class="fechar" onclick="fecharPopup('cadastroPopup')">&times;</span>
                <h2>Criar Conta</h2>
                <div class="mensagem" id="cadastroMsg"></div>
                <form id="cadastroForm" onsubmit="return fazerCadastro(event)">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <input type="text" name="nome" placeholder="Seu nome completo" required minlength="3" maxlength="120">
                    <input type="email" name="email" placeholder="Seu email" required>
                    <input type="password" name="senha" placeholder="Crie uma senha (mín. 6 caracteres)" required minlength="6">
                    <div class="spinner" id="cadastroSpinner"></div>
                    <button type="submit" class="btn" id="cadastroBtn">Criar Conta</button>
                </form>
                <div class="link">
                    Já tem conta? <a onclick="fecharPopup('cadastroPopup'); abrirLogin();">Faça login</a>
                </div>
            </div>
        </div>

        <script>
            // Dados dos cards com informações detalhadas
            const cardDetails = [
                {
                    title: "Gestão de Pessoas",
                    img: "https://images.unsplash.com/photo-1551836022-d5d88e9218df",
                    content: `<strong>Gestão Completa e Eficiente de Pessoas</strong><br><br>
                    Nossa solução de gestão de pessoas oferece uma visão 360° do desempenho de sua equipe. Com ferramentas avançadas de acompanhamento, você pode:<br><br>
                    ✓ Monitorar indicadores de desempenho em tempo real<br>
                    ✓ Identificar talentos internos para promoções<br>
                    ✓ Estabelecer planos de desenvolvimento individualizados<br>
                    ✓ Acompanhar o crescimento profissional de cada membro<br>
                    ✓ Melhorar a produtividade da equipe<br><br>
                    Com nossa plataforma, gestores têm acesso a dados precisos que facilitam tomadas de decisão estratégicas e aumentam o engajamento dos colaboradores.`
                },
                {
                    title: "Resultados Rápidos",
                    img: "https://images.unsplash.com/photo-1522202176988-66273c2fd55f",
                    content: `<strong>Transformação Acelerada em 15 Dias</strong><br><br>
                    Não precisa esperar meses para ver resultados. Nossa metodologia comprovada entrega impactos imediatos:<br><br>
                    ✓ Implementação rápida e sem complicações<br>
                    ✓ Primeiros resultados em até 15 dias<br>
                    ✓ Aumento mensurável de eficiência<br>
                    ✓ ROI positivo em curto prazo<br>
                    ✓ Suporte dedicado durante toda a transição<br><br>
                    Empresas que utilizam nossa solução relatam aumentos de 40% na eficiência operacional do RH e redução significativa de custos administrativos.`
                },
                {
                    title: "Recrutamento Inteligente",
                    img: "https://images.unsplash.com/photo-1521737604893-d14cc237f11d",
                    content: `<strong>Encontre os Melhores Talentos Automaticamente</strong><br><br>
                    Revolucione seu processo de recrutamento com inteligência artificial:<br><br>
                    ✓ Triagem automática de currículos com IA<br>
                    ✓ Identificação de candidatos ideais em segundos<br>
                    ✓ Redução de 60% no tempo de contratação<br>
                    ✓ Diminuição de custos de recrutamento<br>
                    ✓ Melhor alinhamento de candidatos com cultura empresarial<br><br>
                    Automatize os processos seletivos tediosos e foque no que importa: escolher os melhores profissionais para sua equipe.`
                },
                {
                    title: "Simples",
                    img: "https://images.unsplash.com/photo-1522071820081-009f0129c71c",
                    content: `<strong>Interface Intuitiva e Fácil de Usar</strong><br><br>
                    Complexidade zero, eficiência máxima. Nossa plataforma é desenhada para ser usada por qualquer pessoa:<br><br>
                    ✓ Interface amigável sem necessidade de treinamento extenso<br>
                    ✓ Design limpo e organizado<br>
                    ✓ Navegação intuitiva para todos os usuários<br>
                    ✓ Dashboards personalizáveis<br>
                    ✓ Acesso mobile completo<br><br>
                    Gestores, supervisores e funcionários conseguem utilizar a plataforma com facilidade, eliminando a curva de aprendizado.`
                },
                {
                    title: "Seguro",
                    img: "https://images.unsplash.com/photo-1521791136064-7986c2920216",
                    content: `<strong>Proteção Máxima de Dados Empresariais</strong><br><br>
                    Segurança em primeiro lugar. Seus dados estão protegidos com a melhor tecnologia disponível:<br><br>
                    ✓ Criptografia de ponta a ponta<br>
                    ✓ Conformidade com LGPD e GDPR<br>
                    ✓ Backup automático diário<br>
                    ✓ Acesso com autenticação de dois fatores<br>
                    ✓ Auditoria completa de ações<br>
                    ✓ Servidores em data centers certificados<br><br>
                    Sua privacidade e segurança de dados são nossas prioridades. Mantenha informações sensíveis completamente protegidas.`
                },
                {
                    title: "Performance Máxima",
                    img: "https://images.unsplash.com/photo-1552664730-d307ca884978",
                    content: `<strong>Otimização Total de Eficiência Empresarial</strong><br><br>
                    Alcance máxima performance em suas operações de RH:<br><br>
                    ✓ Automação de tarefas repetitivas em 80%<br>
                    ✓ Redução de 50% no tempo de processamento administrativo<br>
                    ✓ Aumento de 45% na produtividade da equipe de RH<br>
                    ✓ Diminuição de erros operacionais<br>
                    ✓ Escalabilidade para crescimento futuro<br><br>
                    Com nossa solução, sua equipe de RH consegue fazer mais em menos tempo, focando em atividades estratégicas que geram real valor para a empresa.`
                }
            ];

            function abrirDetalhes(index) {
                const detalhe = cardDetails[index];
                document.getElementById('cardDetailImg').src = detalhe.img;
                document.getElementById('cardDetailTitle').textContent = detalhe.title;
                document.getElementById('cardDetailContent').innerHTML = detalhe.content;
                document.getElementById('cardModal').classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function fecharDetalhes() {
                document.getElementById('cardModal').classList.remove('show');
                document.body.style.overflow = 'auto';
            }

            // ============================================
            // CONTROLE DE POPUPS
            // ============================================
            function abrirLogin() {
                document.getElementById('loginPopup').classList.add('active');
                document.getElementById('loginMsg').className = 'mensagem';
                document.getElementById('loginMsg').textContent = '';
                document.getElementById('loginForm').reset();
            }

            function abrirCadastro() {
                document.getElementById('cadastroPopup').classList.add('active');
                document.getElementById('cadastroMsg').className = 'mensagem';
                document.getElementById('cadastroMsg').textContent = '';
                document.getElementById('cadastroForm').reset();
            }

            function fecharPopup(id) {
                document.getElementById(id).classList.remove('active');
            }

            // Fecha popup clicando fora
            window.onclick = function(event) {
                if (event.target.classList.contains('popup')) {
                    event.target.classList.remove('active');
                }
                if (event.target.id === 'cardModal') {
                    fecharDetalhes();
                }
            };

            // Fechar modal com tecla ESC
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    fecharDetalhes();
                }
            });

            // ============================================
            // API CALLS
            // ============================================
            async function apiRequest(dados) {
                const response = await fetch('api/auth.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(dados)
                });
                return await response.json();
            }

            // ============================================
            // CADASTRO
            // ============================================
            async function fazerCadastro(event) {
                event.preventDefault();

                const form = document.getElementById('cadastroForm');
                const msg = document.getElementById('cadastroMsg');
                const btn = document.getElementById('cadastroBtn');
                const spinner = document.getElementById('cadastroSpinner');

                btn.disabled = true;
                btn.textContent = 'Cadastrando...';
                spinner.style.display = 'block';
                msg.className = 'mensagem';

                const dados = {
                    acao: 'cadastro',
                    csrf_token: form.csrf_token.value,
                    nome: form.nome.value,
                    email: form.email.value,
                    senha: form.senha.value
                };

                const result = await apiRequest(dados);

                spinner.style.display = 'none';
                btn.disabled = false;
                btn.textContent = 'Criar Conta';

                if (result.erro) {
                    msg.className = 'mensagem erro';
                    msg.textContent = result.mensagem;
                } else {
                    msg.className = 'mensagem sucesso';
                    msg.textContent = result.mensagem;
                    form.reset();
                    setTimeout(() => {
                        fecharPopup('cadastroPopup');
                        abrirLogin();
                    }, 2000);
                }

                return false;
            }

            // ============================================
            // LOGIN
            // ============================================
            async function fazerLogin(event) {
                event.preventDefault();

                const form = document.getElementById('loginForm');
                const msg = document.getElementById('loginMsg');
                const btn = document.getElementById('loginBtn');
                const spinner = document.getElementById('loginSpinner');

                btn.disabled = true;
                btn.textContent = 'Entrando...';
                spinner.style.display = 'block';
                msg.className = 'mensagem';

                const dados = {
                    acao: 'login',
                    csrf_token: form.csrf_token.value,
                    email: form.email.value,
                    senha: form.senha.value
                };

                const result = await apiRequest(dados);

                spinner.style.display = 'none';
                btn.disabled = false;
                btn.textContent = 'Entrar';

                if (result.erro) {
                    msg.className = 'mensagem erro';
                    msg.textContent = result.mensagem;
                } else {
                    msg.className = 'mensagem sucesso';
                    msg.textContent = result.mensagem;
                    if (result.redirect) {
                        setTimeout(() => {
                            window.location.href = result.redirect;
                        }, 500);
                    }
                }

                return false;
            }

            // ============================================
            // LOGOUT (via navbar)
            // ============================================
            document.addEventListener('DOMContentLoaded', function() {
                const btnLogout = document.getElementById('btnLogoutNav');
                if (btnLogout) {
                    btnLogout.addEventListener('click', async function(e) {
                        e.preventDefault();
                        const result = await apiRequest({ acao: 'logout' });
                        if (!result.erro) {
                            window.location.reload();
                        }
                    });
                }
            });
        </script>
    </body>
</html>
