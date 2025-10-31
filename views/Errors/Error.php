<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    font-family: "Poppins", sans-serif;
  }

  body {
    background-color: #f8fafc;
    color: #333;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
  }

  .error-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    max-width: 500px;
    width: 100%;
    text-align: center;
    padding: 2rem;
    animation: fadeIn 0.4s ease-in-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .error-code {
    font-size: 4rem;
    font-weight: 700;
    color: #ef4444;
    margin-bottom: 1rem;
  }

  .error-message {
    font-size: 1.2rem;
    margin-bottom: 1rem;
    color: #555;
  }

  .error-list {
    text-align: left;
    margin-top: 1rem;
    padding: 0.5rem 1rem;
    background: #fef2f2;
    border-left: 4px solid #ef4444;
    border-radius: 8px;
  }

  .error-list ul {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .error-list li {
    color: #b91c1c;
    margin-bottom: 0.3rem;
    font-size: 0.95rem;
  }

  .back-btn {
    display: inline-block;
    margin-top: 1.5rem;
    text-decoration: none;
    background-color: #3b82f6;
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 8px;
    transition: background 0.3s;
  }

  .back-btn:hover {
    background-color: #2563eb;
  }
</style>

<div class="error-container">
  <div class="error-code">⚠️</div>
  <h2 class="error-message">
    <?= htmlspecialchars($message ?? "Une erreur s'est produite.") ?>
  </h2>

  <?php if (!empty($errors)): ?>
  <div class="error-list">
    <strong>Problèmes détectés :</strong>
    <ul>
      <?php foreach ($errors as $error): ?>
      <li>
        •
        <?= htmlspecialchars($error) ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <a href="javascript:history.back()" class="back-btn">← Retour</a>
</div>
