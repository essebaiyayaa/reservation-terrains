<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background: #f9fafb;
    color: #333;
  }

  header {
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
  }

  nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 5%;
    max-width: 1400px;
    margin: 0 auto;
  }

  .logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.5rem;
    font-weight: bold;
    color: #16a34a;
    text-decoration: none;
  }

  .back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
  }

  .back-btn:hover {
    color: #16a34a;
  }

  .container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 5%;
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
  }

  .form-section {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
  }

  .section-title {
    font-size: 1.4rem;
    color: #1f2937;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .section-title i {
    color: #16a34a;
  }

  .user-info {
    background: #f9fafb;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
  }

  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  .info-item {
    display: flex;
    flex-direction: column;
  }

  .info-label {
    font-size: 0.85rem;
    color: #6b7280;
    margin-bottom: 0.25rem;
  }

  .info-value {
    font-weight: 600;
    color: #374151;
  }

  .form-group {
    margin-bottom: 1.5rem;
  }

  .form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: #374151;
    font-weight: 600;
  }

  .form-input,
  .form-select,
  .form-textarea {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s;
  }

  .form-input:focus,
  .form-select:focus,
  .form-textarea:focus {
    outline: none;
    border-color: #16a34a;
  }

  .criteria-row {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
  }

  .btn-search {
    background: #16a34a;
    color: white;
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    white-space: nowrap;
  }

  .btn-search:hover {
    background: #15803d;
  }

  .terrains-list {
    display: none;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
  }

  .terrains-list.active {
    display: grid;
  }

  .terrain-card {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
  }

  .terrain-card:hover {
    border-color: #16a34a;
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.15);
  }

  .terrain-card.selected {
    border-color: #16a34a;
    background: #f0fdf4;
  }

  .terrain-card input[type="radio"] {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 1.25rem;
    height: 1.25rem;
    cursor: pointer;
  }

  .terrain-card h4 {
    font-size: 1.2rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }

  .terrain-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    margin-bottom: 0.3rem;
    font-size: 0.9rem;
  }

  .terrain-detail i {
    width: 16px;
  }

  .terrain-price {
    font-size: 1.3rem;
    font-weight: 700;
    color: #16a34a;
    margin-top: 0.75rem;
  }

  .no-results {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
    display: none;
  }

  .no-results.active {
    display: block;
  }

  .time-slots {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 0.75rem;
    margin-top: 0.5rem;
  }

  .time-slot {
    position: relative;
  }

  .time-slot input[type="radio"] {
    display: none;
  }

  .time-slot label {
    display: block;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: 500;
  }

  .time-slot input[type="radio"]:checked + label {
    background: #16a34a;
    color: white;
    border-color: #16a34a;
  }

  .time-slot label:hover {
    border-color: #16a34a;
  }

  .time-slot.disabled label {
    background: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
    border-color: #e5e7eb;
  }

  .options-list {
    display: grid;
    gap: 1rem;
  }

  .option-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
  }

  .option-item:hover {
    border-color: #16a34a;
  }

  .option-item.selected {
    border-color: #16a34a;
    background: #f0fdf4;
  }

  .option-checkbox {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .option-checkbox input {
    width: 1.25rem;
    height: 1.25rem;
    cursor: pointer;
  }

  .option-name {
    font-weight: 600;
    color: #374151;
  }

  .option-price {
    color: #16a34a;
    font-weight: 700;
  }

  .sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
  }

  .cart {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
  }

  .cart-title {
    font-size: 1.3rem;
    color: #1f2937;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .cart-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
  }

  .cart-item:last-child {
    border-bottom: none;
  }

  .cart-item-name {
    color: #6b7280;
  }

  .cart-item-price {
    font-weight: 600;
    color: #374151;
  }

  .cart-total {
    background: #f0fdf4;
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
  }

  .cart-total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
  }

  .cart-total-label {
    font-weight: 600;
    color: #065f46;
  }

  .cart-total-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #16a34a;
  }

  .btn-submit {
    width: 100%;
    background: #16a34a;
    color: white;
    padding: 1rem;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 1rem;
  }

  .btn-submit:hover:not(:disabled) {
    background: #15803d;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
  }

  .btn-submit:disabled {
    background: #9ca3af;
    cursor: not-allowed;
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

  .hidden {
    display: none;
  }

  @media (max-width: 1024px) {
    .container {
      grid-template-columns: 1fr;
    }

    .sidebar {
      position: static;
    }

    .criteria-row {
      grid-template-columns: 1fr;
    }
  }
</style>


<div class="container">
  <div class="form-section">
    <?php if (isset($error)): ?>
    <div class="alert alert-error">
      <?php echo htmlspecialchars($error); ?>
    </div>
    <?php endif; ?>

    <!-- Informations utilisateur -->
    <h3 class="section-title">
      <i class="fas fa-user"></i>
      Vos informations
    </h3>
    <div class="user-info">
      <div class="info-grid">
        <div class="info-item">
          <span class="info-label">Nom complet</span>
          <span class="info-value">
            <?php 
                            if (isset($user['prenom']) && isset($user['nom'])) {
                                echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']);
                            } else {
                                echo 'Information non disponible';
                            }
                            ?>
          </span>
        </div>
        <div class="info-item">
          <span class="info-label">Email</span>
          <span class="info-value">
            <?php 
                            if (isset($user['email'])) {
                                echo htmlspecialchars($user['email']);
                            } else {
                                echo 'Information non disponible';
                            }
                            ?>
          </span>
        </div>
        <div class="info-item">
          <span class="info-label">Téléphone</span>
          <span class="info-value">
            <?php 
                            if (isset($user['telephone']) && !empty($user['telephone'])) {
                                echo htmlspecialchars($user['telephone']);
                            } else {
                                echo 'Non renseigné';
                            }
                            ?>
          </span>
        </div>
      </div>
    </div>

    <form method="POST" id="reservationForm">
      <!-- Critères de recherche -->
      <h3 class="section-title">
        <i class="fas fa-search"></i>
        Rechercher un terrain
      </h3>

      <div class="criteria-row">
        <div class="form-group" style="margin-bottom: 0">
          <label class="form-label">Type de terrain</label>
          <select id="searchType" class="form-select">
            <option value="">Tous les types</option>
            <?php foreach ($types as $type): ?>
            <option value="<?php echo htmlspecialchars($type); ?>">
              <?php echo htmlspecialchars($type); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group" style="margin-bottom: 0">
          <label class="form-label">Taille</label>
          <select id="searchTaille" class="form-select">
            <option value="">Toutes les tailles</option>
            <?php foreach ($tailles as $taille): ?>
            <option value="<?php echo htmlspecialchars($taille); ?>">
              <?php echo htmlspecialchars($taille); ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
             
                
            <button type="button" class="btn-search" onclick="searchTerrains()">
              <i class="fas fa-search"></i> Rechercher
            </button>
             
        
      </div>

      <!-- Liste des terrains -->
      <div id="terrainsListContainer" style="margin-top: 2rem">
        <div class="terrains-list" id="terrainsList"></div>
        <div class="no-results" id="noResults">
          <i
            class="fas fa-info-circle"
            style="font-size: 3rem; color: #d1d5db; margin-bottom: 1rem"
          ></i>
          <p>Aucun terrain trouvé avec ces critères</p>
        </div>
      </div>

      <!-- Sections cachées jusqu'à la sélection d'un terrain -->
      <div id="reservationDetails" class="hidden">
        <input type="hidden" name="id_terrain" id="selectedTerrainId" />

        <!-- Date de réservation -->
        <div class="form-group" style="margin-top: 2rem">
          <label class="form-label">
            <i class="fas fa-calendar"></i>
            Date de réservation
          </label>
          <input
            type="date"
            name="date_reservation"
            id="dateReservation"
            class="form-input"
            min="<?php echo date('Y-m-d'); ?>"
            required
          />
        </div>

        <!-- Créneaux horaires -->
        <div class="form-group">
          <label class="form-label">
            <i class="fas fa-clock"></i>
            Créneaux horaires disponibles
          </label>
          <div id="timeSlotsContainer">
            <p style="color: #6b7280; text-align: center; padding: 2rem">
              Veuillez sélectionner une date pour voir les créneaux disponibles
            </p>
          </div>
        </div>

        
        <!-- Options supplémentaires -->
        <?php if (!empty($options)): ?>
        <div class="form-group">
          <h3 class="section-title">
            <i class="fas fa-plus-circle"></i>
            Options supplémentaires
          </h3>
          <div class="options-list">
            <?php foreach ($options as $option): ?>
              
            <div
              class="option-item"
              onclick="toggleOption(this, <?php echo $option['id_option']; ?>, <?php echo $option['prix']; ?>)"
            >
              <div class="option-checkbox">
                <input
                  type="checkbox"
                  name="options[]"
                  value="<?php echo $option['id_option']; ?>"
                  id="option_<?php echo $option['id_option']; ?>"
                  data-price="<?php echo $option['prix']; ?>"
                />
                <span class="option-name"
                  ><?php echo htmlspecialchars($option['nom_option']); ?></span
                >
              </div>
              <span class="option-price"
                ><?php echo number_format($option['prix'], 2); ?>
                DH</span
              >
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Commentaires -->
        <div class="form-group">
          <label class="form-label">
            <i class="fas fa-comment"></i>
            Commentaires (optionnel)
          </label>
          <textarea
            name="commentaires"
            class="form-textarea"
            rows="4"
            placeholder="Ajoutez des remarques ou demandes spécifiques..."
          ></textarea>
        </div>
      </div>
    </form>
  </div>

  <!-- Sidebar - Panier -->
  <div class="sidebar">
    <div class="cart">
      <h3 class="cart-title">
        <i class="fas fa-shopping-cart"></i>
        Récapitulatif
      </h3>

      <div id="cartItems">
        <div class="cart-item">
          <span class="cart-item-name">Terrain</span>
          <span class="cart-item-price" id="terrainPrice">0.00 DH</span>
        </div>
        <div id="optionsCart"></div>
      </div>

      <div class="cart-total">
        <div class="cart-total-row">
          <span class="cart-total-label">Total</span>
          <span class="cart-total-value" id="totalPrice">0.00 DH</span>
        </div>
      </div>

      <button
        type="submit"
        form="reservationForm"
        name="submit_reservation"
        class="btn-submit"
        id="submitBtn"
        disabled
      >
        <i class="fas fa-calendar-check"></i>
        Confirmer la réservation
      </button>
    </div>
  </div>
</div>

<script>
 // ============================================================================
// CONFIGURATION - Adapter selon votre structure
// ============================================================================

// Calculer le chemin relatif depuis views/Client vers public/api
const API_BASE_PATH = 'http://localhost/reservation-terrains/public/api';


const API_CONFIG = {
    getSlots: `${API_BASE_PATH}/get_available_slots.php`,
    searchTerrains: `${API_BASE_PATH}/search_terrains.php`
};

// Variables globales
let selectedTerrain = null;
let selectedOptions = {};
let selectedTimeSlot = null;
let pollingInterval = null;
let currentDate = null;
let currentTerrainId = null;
let lastBookedSlots = [];
let isPollingActive = false;
let isSubmitting = false;

// Créneaux horaires (8h à 22h)
const timeSlots = [];
for (let hour = 8; hour < 22; hour++) {
    timeSlots.push(`${hour.toString().padStart(2, '0')}:00:00`);
}

// ============================================================================
// FONCTIONS DE POLLING
// ============================================================================

function startSlotsPolling(terrainId, date) {
    if (isPollingActive && currentTerrainId === terrainId && currentDate === date) {
        console.log('⚠️ Polling déjà actif pour ce terrain/date');
        return;
    }

    stopSlotsPolling();
    currentDate = date;
    currentTerrainId = terrainId;
    isPollingActive = true;

    console.log(`🔄 Polling démarré: Terrain ${terrainId}, Date ${date}`);
    loadAvailableSlots(terrainId, date, false);

    pollingInterval = setInterval(() => {
        if (isPollingActive) {
            checkSlotsAvailability(terrainId, date);
        }
    }, 10000); // 10 secondes
}

function stopSlotsPolling() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
        console.log('⏹️ Polling arrêté');
    }
    isPollingActive = false;
}

function checkSlotsAvailability(terrainId, date) {
    const url = `${API_CONFIG.getSlots}?terrain_id=${terrainId}&date=${date}&_=${Date.now()}`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data.success && data.booked_slots) {
                const hasChanges = checkForChanges(data.booked_slots);
                
                if (hasChanges) {
                    console.log('🔔 Nouveaux créneaux réservés détectés');
                    updateTimeSlotsDisplay(data.booked_slots);
                    lastBookedSlots = [...data.booked_slots];
                    
                    if (selectedTimeSlot && data.booked_slots.includes(selectedTimeSlot.value)) {
                        showSlotUnavailableWarning();
                        selectedTimeSlot = null;
                        updateSubmitButton();
                    }
                }
            }
        })
        .catch(error => {
            console.error('❌ Erreur polling:', error);
        });
}

function checkForChanges(newBookedSlots) {
    if (lastBookedSlots.length !== newBookedSlots.length) return true;
    return newBookedSlots.some(slot => !lastBookedSlots.includes(slot));
}

function updateTimeSlotsDisplay(bookedSlots) {
    const timeSlotElements = document.querySelectorAll('.time-slot');
    
    timeSlotElements.forEach(slotElement => {
        const radio = slotElement.querySelector('input[type="radio"]');
        if (!radio) return;
        
        const slotValue = radio.value;
        const isBooked = bookedSlots.includes(slotValue);
        
        if (isBooked && !slotElement.classList.contains('disabled')) {
            slotElement.classList.add('disabled');
            radio.disabled = true;
            radio.checked = false;
            
            slotElement.style.transition = 'all 0.3s ease';
            slotElement.style.opacity = '0.5';
            setTimeout(() => slotElement.style.opacity = '1', 300);
        } else if (!isBooked && slotElement.classList.contains('disabled')) {
            slotElement.classList.remove('disabled');
            radio.disabled = false;
        }
    });
}

function showSlotUnavailableWarning() {
    const container = document.getElementById('timeSlotsContainer');
    
    const warning = document.createElement('div');
    warning.style.cssText = `
        background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
        padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;
        font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;
        animation: slideDown 0.3s ease;
    `;
    warning.innerHTML = `
        <i class="fas fa-exclamation-circle"></i>
        <span>Le créneau que vous aviez sélectionné vient d'être réservé. Veuillez en choisir un autre.</span>
    `;
    
    container.insertBefore(warning, container.firstChild);
    setTimeout(() => {
        warning.style.animation = 'slideUp 0.3s ease';
        setTimeout(() => warning.remove(), 300);
    }, 5000);
}

// ============================================================================
// FONCTIONS DE CHARGEMENT DES DONNÉES
// ============================================================================

function loadAvailableSlots(terrainId, date, startPolling = true) {
    if (startPolling) stopSlotsPolling();
    
    const url = `${API_CONFIG.getSlots}?terrain_id=${terrainId}&date=${date}&_=${Date.now()}`;
    console.log('📡 Chargement créneaux:', url);
    
    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return response.json();
        })
        .then(data => {
            console.log('✅ Réponse API:', data);
            
            if (data.success) {
                displayTimeSlots(data.booked_slots || []);
                lastBookedSlots = [...(data.booked_slots || [])];
                selectedTimeSlot = null;
                updateSubmitButton();

                if (startPolling) {
                    startSlotsPolling(terrainId, date);
                }
            } else {
                console.error('❌ Erreur API:', data.message);
                alert('Erreur: ' + data.message);
                displayTimeSlots([]);
            }
        })
        .catch(error => {
            console.error('❌ Erreur fetch:', error);
            alert('Impossible de charger les créneaux. Vérifiez la console (F12).');
            displayTimeSlots([]);
        });
}

function displayTimeSlots(bookedSlots) {
    const container = document.getElementById('timeSlotsContainer');
    let html = '<div class="time-slots">';

    timeSlots.forEach(slot => {
        const isBooked = bookedSlots.includes(slot);
        const [hour] = slot.split(':');
        const displayTime = `${hour}h-${parseInt(hour) + 1}h`;

        html += `
            <div class="time-slot ${isBooked ? 'disabled' : ''}">
                <input type="radio" name="heure_debut" value="${slot}" 
                       id="slot_${slot}" ${isBooked ? 'disabled' : ''}
                       onchange="selectTimeSlot(this)">
                <label for="slot_${slot}">${displayTime}</label>
            </div>
        `;
    });

    html += '</div>';
    container.innerHTML = html;
}

function searchTerrains() {
    const type = document.getElementById('searchType').value;
    const taille = document.getElementById('searchTaille').value;
    const url = `${API_CONFIG.searchTerrains}?type=${encodeURIComponent(type)}&taille=${encodeURIComponent(taille)}`;
    
    console.log('🔍 Recherche terrains:', url);

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(data => {
            console.log('✅ Terrains trouvés:', data.length || data);
            displayTerrains(data);
        })
        .catch(error => {
            console.error('❌ Erreur:', error);
            alert('Erreur lors de la recherche des terrains');
        });
}

function displayTerrains(terrains) {
    const container = document.getElementById('terrainsList');
    const noResults = document.getElementById('noResults');

    if (!terrains || terrains.length === 0) {
        container.classList.remove('active');
        noResults.classList.add('active');
        return;
    }

    noResults.classList.remove('active');
    container.classList.add('active');

    let html = '';
    terrains.forEach(terrain => {
        html += `
            <div class="terrain-card" onclick="selectTerrain(${terrain.id_terrain}, '${terrain.nom_terrain}', ${terrain.prix_heure})">
                <input type="radio" name="terrain_radio" value="${terrain.id_terrain}">
                <h4>${terrain.nom_terrain}</h4>
                <div class="terrain-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${terrain.adresse}, ${terrain.ville}</span>
                </div>
                <div class="terrain-detail">
                    <i class="fas fa-expand"></i>
                    <span>${terrain.taille}</span>
                </div>
                <div class="terrain-detail">
                    <i class="fas fa-layer-group"></i>
                    <span>${terrain.type}</span>
                </div>
                <div class="terrain-price">${parseFloat(terrain.prix_heure).toFixed(2)} DH/h</div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// ============================================================================
// FONCTIONS DE SÉLECTION
// ============================================================================

function selectTerrain(id, nom, prix) {
    document.querySelectorAll('.terrain-card').forEach(card => {
        card.classList.remove('selected');
    });

    event.currentTarget.classList.add('selected');
    event.currentTarget.querySelector('input[type="radio"]').checked = true;

    selectedTerrain = { id, nom, prix };
    document.getElementById('selectedTerrainId').value = id;
    document.getElementById('terrainPrice').textContent = parseFloat(prix).toFixed(2) + ' DH';
    document.getElementById('reservationDetails').classList.remove('hidden');

    selectedTimeSlot = null;
    document.getElementById('timeSlotsContainer').innerHTML = `
        <p style="color: #6b7280; text-align: center; padding: 2rem;">
            Veuillez sélectionner une date pour voir les créneaux disponibles
        </p>
    `;

    updateCart();
    updateSubmitButton();
}

function selectTimeSlot(radio) {
    selectedTimeSlot = radio;
    updateSubmitButton();
}

function toggleOption(element, optionId, price) {
    const checkbox = element.querySelector('input[type="checkbox"]');
    checkbox.checked = !checkbox.checked;

    if (checkbox.checked) {
        selectedOptions[optionId] = {
            price: price,
            name: element.querySelector('.option-name').textContent
        };
        element.classList.add('selected');
    } else {
        delete selectedOptions[optionId];
        element.classList.remove('selected');
    }

    updateCart();
}

function updateCart() {
    const optionsCart = document.getElementById('optionsCart');
    let html = '';
    let optionsTotal = 0;

    for (const [optionId, option] of Object.entries(selectedOptions)) {
        optionsTotal += parseFloat(option.price);
        html += `
            <div class="cart-item">
                <span class="cart-item-name">${option.name}</span>
                <span class="cart-item-price">${parseFloat(option.price).toFixed(2)} DH</span>
            </div>
        `;
    }

    optionsCart.innerHTML = html;

    const terrainPrice = selectedTerrain ? selectedTerrain.prix : 0;
    const total = terrainPrice + optionsTotal;
    document.getElementById('totalPrice').textContent = total.toFixed(2) + ' DH';
}

function updateSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    const dateSelected = document.getElementById('dateReservation').value;

    if (selectedTerrain && dateSelected && selectedTimeSlot) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// ============================================================================
// GESTION DU FORMULAIRE
// ============================================================================

function handleFormSubmit(e) {
    e.preventDefault();

    const dateSelected = document.getElementById('dateReservation').value;
    const terrainId = document.getElementById('selectedTerrainId').value;

    if (!terrainId || !dateSelected || !selectedTimeSlot) {
        alert('Veuillez remplir tous les champs requis');
        return false;
    }

    if (isSubmitting) return false;
    isSubmitting = true;

    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Vérification...';

    const selectedSlotValue = selectedTimeSlot.value;
    const url = `${API_CONFIG.getSlots}?terrain_id=${terrainId}&date=${dateSelected}&_=${Date.now()}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.booked_slots) {
                if (data.booked_slots.includes(selectedSlotValue)) {
                    alert('Désolé, ce créneau vient d\'être réservé. Veuillez en choisir un autre.');
                    
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-calendar-check"></i> Confirmer la réservation';
                    isSubmitting = false;
                    
                    updateTimeSlotsDisplay(data.booked_slots);
                    selectedTimeSlot = null;
                    updateSubmitButton();
                    return false;
                }
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Réservation en cours...';
                document.getElementById('reservationForm').submit();
            } else {
                throw new Error('Erreur lors de la vérification');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue. Veuillez réessayer.');
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-calendar-check"></i> Confirmer la réservation';
            isSubmitting = false;
        });

    return false;
}

// ============================================================================
// INITIALISATION
// ============================================================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initialisation du système de réservation');
    console.log('📂 Chemins API:', API_CONFIG);
    
    const dateInput = document.getElementById('dateReservation');
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            const date = this.value;
            const terrainId = document.getElementById('selectedTerrainId').value;
            
            if (date && terrainId) {
                loadAvailableSlots(terrainId, date);
            }
        });
    }

    const form = document.getElementById('reservationForm');
    if (form) {
        form.addEventListener('submit', handleFormSubmit);
    }

    window.addEventListener('beforeunload', () => stopSlotsPolling());

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopSlotsPolling();
        } else if (currentDate && currentTerrainId) {
            startSlotsPolling(currentTerrainId, currentDate);
        }
    });

    updateCart();
    updateSubmitButton();
    
    console.log('✅ Initialisation terminée');
});

// Animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
`;
document.head.appendChild(style);
</script>
