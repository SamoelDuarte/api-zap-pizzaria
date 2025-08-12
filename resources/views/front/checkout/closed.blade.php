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
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5 shadow-lg border-0">
                <div class="card-body text-center">
                    <h3 class="card-title text-danger mb-4">Estamos Fechados Hoje</h3>
                    <p class="card-text h5 text-muted">
                        A Pizzaria Integra não abre às terças-feiras, mas estaremos prontos para recebê-lo
                        com todo carinho e sabor nos demais dias da semana.
                    </p>
                    <div class="mt-4">
                        <i class="fas fa-pizza-slice fa-3x text-warning"></i>
                    </div>
                    <p class="mt-4 text-secondary">
                        Te esperamos amanhã para saborear nossas pizzas artesanais, feitas com ingredientes selecionados
                        e aquele toque especial que você já conhece.
                    </p>
                    <p class="mt-3 font-italic">
                        "O sabor da sua alegria está aqui!"
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
