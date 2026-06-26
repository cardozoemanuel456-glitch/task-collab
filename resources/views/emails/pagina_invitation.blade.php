<!DOCTYPE html>
<html>

<head>
  <style>
    body {
      font-family: sans-serif;
      line-height: 1.6;
      color: #333;
      background: #f9fafb;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #1f2937;
    }

    .btn {
      display: inline-block;
      padding: 12px 24px;
      background: #4f46e5;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      margin: 20px 0;
    }

    .code-box {
      background: #f3f4f6;
      padding: 15px;
      text-align: center;
      font-size: 28px;
      font-weight: bold;
      letter-spacing: 5px;
      border: 2px dashed #d1d5db;
      margin: 25px 0;
      border-radius: 8px;
      color: #111827;
    }

    .footer {
      margin-top: 30px;
      font-size: 12px;
      color: #6b7280;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>¡Hola! {{ $inviterName }} te ha invitado</h2>
    <p>Has sido invitado a unirte a la página: <strong>{{ $paginaName }}</strong>.</p>

    <p>Puedes unirte haciendo clic en el botón o copiando y pegando el código de acceso:</p>

    <div class="code-box">
      {{ $inviteCode }}
    </div>

    <p style="text-align: center;">
      <a href="{{ $inviteUrl }}" class="btn">Unirme a la Página</a>
    </p>

    <div class="footer">
      <p>Este código expirará en 48 horas.</p>
      <p>Si no esperabas esta invitación, puedes ignorar este correo.</p>
    </div>
  </div>
</body>

</html>