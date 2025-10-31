<form method="POST">
  <div class="form-row">
    <div class="form-group">
      <label for="nom">Nom *</label>
      <input
        type="text"
        id="nom"
        name="nom"
        required
      />
    </div>

    <div class="form-group">
      <label for="prenom">Prénom *</label>
      <input
        type="text"
        id="prenom"
        name="prenom"
        required
      />
    </div>
  </div>

  <div class="form-group">
    <label for="email">Email *</label>
    <input
      type="email"
      id="email"
      name="email"
      required
    />
  </div>

  <div class="form-group">
    <label for="telephone">Téléphone</label>
    <input
      type="tel"
      id="telephone"
      name="telephone"
     
    />
  </div>

  <div class="form-group">
    <label for="mot_de_passe">Mot de passe * (min. 8 caractères)</label>
    <div class="password-wrapper">
      <input type="password" id="mot_de_passe" name="mot_de_passe" required />
      <button
        type="button"
        class="toggle-password"
        onclick="togglePassword('mot_de_passe')"
      >
        <i class="fas fa-eye" id="eye-mot_de_passe"></i>
      </button>
    </div>
  </div>

  <div class="form-group">
    <label for="confirmer_mot_de_passe">Confirmer le mot de passe *</label>
    <div class="password-wrapper">
      <input
        type="password"
        id="confirmer_mot_de_passe"
        name="confirmer_mot_de_passe"
        required
      />
      <button
        type="button"
        class="toggle-password"
        onclick="togglePassword('confirmer_mot_de_passe')"
      >
        <i class="fas fa-eye" id="eye-confirmer_mot_de_passe"></i>
      </button>
    </div>
  </div>

  <div class="recaptcha-wrapper">
    <div
      class="g-recaptcha"
      data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"
    ></div>
  </div>

  <button type="submit" class="btn-submit">
    <i class="fas fa-user-plus"></i> Créer mon compte
  </button>
</form>

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  /* Menu utilisateur */
  .user-menu {
    position: relative;
  }

  .user-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: #16a34a;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s;
  }

  .user-button:hover {
    background: #15803d;
  }

  .user-button i.fa-user-circle {
    font-size: 1.5rem;
  }

  .user-button i.fa-chevron-down {
    font-size: 0.8rem;
    transition: transform 0.3s;
  }

  .user-button:hover i.fa-chevron-down {
    transform: rotate(180deg);
  }

  .dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 0.5rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    min-width: 220px;
    display: none;
    z-index: 1000;
  }

  .dropdown-menu.show {
    display: block;
    animation: fadeIn 0.2s;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .dropdown-menu a {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.25rem;
    color: #374151;
    text-decoration: none;
    transition: background 0.2s;
  }

  .dropdown-menu a:hover {
    background: #f3f4f6;
  }

  .dropdown-menu a.logout {
    color: #dc2626;
  }

  .dropdown-menu a.logout:hover {
    background: #fee2e2;
  }

  .dropdown-menu hr {
    margin: 0.5rem 0;
    border: none;
    border-top: 1px solid #e5e7eb;
  }
  body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
  }

  .register-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    width: 100%;
    padding: 2.5rem;
  }

  .register-header {
    text-align: center;
    margin-bottom: 2rem;
  }

  .logo {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 2rem;
    font-weight: bold;
    color: #16a34a;
    margin-bottom: 0.5rem;
  }

  .register-header h1 {
    font-size: 1.8rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }

  .register-header p {
    color: #6b7280;
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  label {
    display: block;
    margin-bottom: 0.5rem;
    color: #374151;
    font-weight: 500;
  }

  input[type="text"],
  input[type="email"],
  input[type="tel"],
  input[type="password"] {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s;
  }

  input:focus {
    outline: none;
    border-color: #16a34a;
  }

  .password-wrapper {
    position: relative;
  }

  .toggle-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 0.5rem;
  }

  .toggle-password:hover {
    color: #16a34a;
  }

  .alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
  }

  .alert-error {
    background: #fee2e2;
    border: 1px solid #fecaca;
    color: #991b1b;
  }

  .alert-success {
    background: #d1fae5;
    border: 1px solid #a7f3d0;
    color: #065f46;
  }

  .alert ul {
    margin-left: 1.5rem;
  }

  .recaptcha-wrapper {
    margin: 1.5rem 0;
    display: flex;
    justify-content: center;
  }

  .btn-submit {
    width: 100%;
    padding: 1rem;
    background: #16a34a;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
  }

  .btn-submit:hover {
    background: #15803d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
  }

  .btn-submit:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    transform: none;
  }

  .login-link {
    text-align: center;
    margin-top: 1.5rem;
    color: #6b7280;
  }

  .login-link a {
    color: #16a34a;
    text-decoration: none;
    font-weight: 600;
  }

  .login-link a:hover {
    text-decoration: underline;
  }

  .back-home {
    text-align: center;
    margin-top: 1rem;
  }

  .back-home a {
    color: #6b7280;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .back-home a:hover {
    color: #16a34a;
  }

  @media (max-width: 768px) {
    .form-row {
      grid-template-columns: 1fr;
    }

    .register-container {
      padding: 1.5rem;
    }
  }
</style>
