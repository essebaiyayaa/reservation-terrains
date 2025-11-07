<?php 
$title = 'FootBooking - Réservation de Terrains de Foot';

?>

<style>
    /* Hero Section */
    .hero {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        color: white;
        padding: 6rem 5% 8rem;
        text-align: center;
    }

    .hero-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .hero h1 {
        font-size: 3rem;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .hero p {
        font-size: 1.3rem;
        margin-bottom: 2.5rem;
        opacity: 0.95;
    }

    .btn-hero {
        font-size: 1.2rem;
        padding: 1rem 2.5rem;
        background: white;
        color: #16a34a;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    .btn-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.3);
    }

    /* Comment ça marche Section */
    .how-it-works {
        padding: 5rem 5%;
        background: #f9fafb;
    }

    .section-title {
        text-align: center;
        font-size: 2.5rem;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .section-subtitle {
        text-align: center;
        color: #6b7280;
        font-size: 1.1rem;
        margin-bottom: 4rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 3rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .step {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .step:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .step-icon {
        width: 80px;
        height: 80px;
        background: #dcfce7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
    }

    .step h3 {
        font-size: 1.4rem;
        color: #1f2937;
        margin-bottom: 1rem;
    }

    .step p {
        color: #6b7280;
        line-height: 1.7;
    }

    /* Pourquoi FootBooking Section */
    .why-footbooking {
        padding: 5rem 5%;
        background: white;
    }

    .features {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2.5rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .feature {
        display: flex;
        gap: 1.5rem;
    }

    .feature-icon {
        flex-shrink: 0;
        width: 60px;
        height: 60px;
        background: #dcfce7;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #16a34a;
        font-size: 1.8rem;
    }

    .feature-content h3 {
        font-size: 1.3rem;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .feature-content p {
        color: #6b7280;
        line-height: 1.7;
    }

    @media (max-width: 768px) {
        .hero h1 {
            font-size: 2rem;
        }

        .hero p {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .steps, .features {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Réservez votre terrain de foot en quelques clics !</h1>
        <p>Choisissez la date, l'heure et le type de terrain selon vos préférences.</p>
        <a href="<?= UrlHelper::url('terrains') ?>" class="btn btn-hero">
            Réserver maintenant
        </a>
    </div>
</section>

<!-- Comment ça marche Section -->
<section class="how-it-works">
    <h2 class="section-title">Comment ça marche ?</h2>
    <p class="section-subtitle">Réserver un terrain de football n'a jamais été aussi simple. Suivez ces 3 étapes !</p>
    
    <div class="steps">
        <div class="step">
            <div class="step-icon"><i class="fa-solid fa-calendar-days"></i></div>
            <h3>Choisissez la date</h3>
            <p>Sélectionnez la date qui vous convient pour votre match.</p>
        </div>

        <div class="step">
            <div class="step-icon"><i class="fa-solid fa-clock"></i></div>
            <h3>Sélectionnez l'heure</h3>
            <p>Choisissez votre créneau horaire parmi les disponibilités (16h-17h, 17h-18h, 18h-19h...).</p>
        </div>

        <div class="step">
            <div class="step-icon"><i class="fa-solid fa-futbol"></i></div>
            <h3>Configurez votre terrain</h3>
            <p>Sélectionnez la taille (Mini foot, Terrain moyen, Grand terrain) et le type de gazon (naturel/artificiel/dur).</p>
        </div>
    </div>
</section>

<!-- Pourquoi FootBooking Section -->
<section class="why-footbooking">
    <h2 class="section-title">Pourquoi choisir FootBooking ?</h2>
    <p class="section-subtitle">La plateforme complète pour gérer toutes vos réservations de terrains de football</p>
    
    <div class="features">
        <div class="feature">
            <div class="feature-icon"><i class="fas fa-rocket"></i></div>
            <div class="feature-content">
                <h3>Réservation rapide</h3>
                <p>Réservez votre terrain en moins de 2 minutes. Interface intuitive et processus simplifié pour une expérience optimale.</p>
            </div>
        </div>

        <div class="feature">
            <div class="feature-icon"><i class="fas fa-rotate"></i></div>
            <div class="feature-content">
                <h3>Modification flexible</h3>
                <p>Modifiez votre réservation jusqu'à 48 heures avant le match selon la disponibilité du terrain.</p>
            </div>
        </div>

        <div class="feature">
            <div class="feature-icon"><i class="fas fa-chart-column"></i></div>
            <div class="feature-content">
                <h3>Disponibilité en temps réel</h3>
                <p>Consultez la disponibilité des terrains en temps réel et choisissez le créneau qui vous convient le mieux.</p>
            </div>
        </div>

        <div class="feature">
            <div class="feature-icon"><i class="fas fa-bullseye"></i></div>
            <div class="feature-content">
                <h3>Options personnalisables</h3>
                <p>Personnalisez votre expérience avec des services optionnels : ballon, arbitre, maillots, douche, casiers...</p>
            </div>
        </div>
    </div>
</section>
