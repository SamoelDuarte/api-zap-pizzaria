<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Integra Pizzaria</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Arial', sans-serif;
      background: #fff8f0;
      color: #333;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      text-align: center;
    }

    .container {
      padding: 20px;
      max-width: 400px;
      width: 90%;
      background: white;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h1 {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    p {
      margin-bottom: 20px;
      font-size: 1rem;
    }

    input[type="tel"] {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 15px;
    }

    button {
      background-color: #e63946;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #d62828;
    }

    .logo {
      font-size: 1.5rem;
      color: #e63946;
      margin-bottom: 20px;
      display: inline-block;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="logo"><i class="fa-solid fa-pizza-slice"></i> Integra Pizzaria</div>
    <h1>Bem-vindo!</h1>
    <p>Faça seu pedido agora mesmo. Digite seu número de celular:</p>
    
    <input type="tel" id="phone" placeholder="Ex: 11999999999" />
    <button onclick="fazerPedido()">Fazer Pedido</button>
  </div>

  <script>
    function fazerPedido() {
      let phone = document.getElementById('phone').value.trim();
      phone = phone.replace(/\D/g, ''); // Remove qualquer caractere que não for número

      if (phone.length < 10 || phone.length > 13) {
        alert('Por favor, insira um número de celular válido.');
        return;
      }

      if (!phone.startsWith('55')) {
        phone = '55' + phone;
      }

      window.location.href = `/checkout/pedido/${phone}`;
    }
  </script>
</body>
</html>
