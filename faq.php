<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'faq';
$pageTitle   = "FAQ – Questions fréquentes | IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<style>
/* ===== HERO FAQ ===== */
.faq-hero {
  background: linear-gradient(135deg, #1340B0 0%, #1B4FD8 60%, #1E57E8 100%);
  padding: 70px 0 55px;
  position: relative; overflow: hidden;
}
.faq-hero::before {
  content:''; position:absolute; top:-80px; right:-80px;
  width:380px; height:380px; border-radius:50%;
  background:radial-gradient(circle,rgba(232,40,42,0.07) 0%,transparent 70%);
}
.faq-hero .kicker {
  font-size:11px; font-weight:800; letter-spacing:0.15em;
  text-transform:uppercase; color:#E8282A; margin-bottom:14px;
}
.faq-hero h1 {
  font-size: clamp(24px, 3.2vw, 42px); font-weight:900;
  color:#fff; margin:0 0 14px; letter-spacing:-0.02em; line-height:1.2;
}
.faq-hero h1 span { color:#E8282A; }
.faq-hero .desc { font-size:16px; color:rgba(255,255,255,0.65); max-width:620px; line-height:1.75; }

/* ===== ACCORDION FAQ ===== */
.faq-section { padding: 70px 0; background: #FAF7F0; }
.faq-category-title {
  font-size: 11px; font-weight: 800; letter-spacing: 0.12em;
  text-transform: uppercase; color: #C01A1C;
  margin: 48px 0 20px; display: flex; align-items: center; gap: 12px;
}
.faq-category-title:first-of-type { margin-top: 0; }
.faq-category-title::after { content:''; flex:1; height:1px; background:rgba(232,40,42,0.2); }

.faq-item {
  background: #fff;
  border-radius: 14px;
  margin-bottom: 10px;
  border: 1px solid rgba(27,79,216,0.06);
  overflow: hidden;
  transition: box-shadow 0.3s ease;
}
.faq-item:hover { box-shadow: 0 6px 24px rgba(27,79,216,0.09); }
.faq-question {
  display: flex; justify-content: space-between; align-items: center;
  padding: 20px 24px; cursor: pointer; gap: 16px;
  font-size: 15px; font-weight: 700; color: #fff;
  user-select: none;
}
.faq-question:hover { color: #C01A1C; }
.faq-question .faq-icon {
  width: 28px; height: 28px; flex-shrink: 0;
  background: rgba(232,40,42,0.1); border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: #C01A1C; font-size: 12px;
  transition: all 0.3s ease;
}
.faq-item.open .faq-icon { background: #E8282A; color: #fff; transform: rotate(45deg); }
.faq-answer {
  display: none;
  padding: 0 24px 20px;
  font-size: 14px; color: #4B5563; line-height: 1.75;
  border-top: 1px solid rgba(27,79,216,0.05);
}
.faq-answer p { margin: 14px 0 0; }
.faq-item.open .faq-answer { display: block; }

/* ===== CTA ===== */
.faq-cta {
  padding: 60px 0;
  background: linear-gradient(135deg, #1340B0, #1B4FD8);
  text-align: center;
}
.faq-cta h2 { font-size: clamp(20px, 2.5vw, 30px); font-weight:900; color:#fff; margin-bottom:10px; }
.faq-cta p { color:rgba(255,255,255,0.6); margin-bottom:28px; font-size:15px; }
.faq-cta-btn {
  display:inline-flex; align-items:center; gap:8px;
  padding:14px 30px; border-radius:999px;
  background:linear-gradient(135deg,#E8282A,#C01A1C);
  color:#1B4FD8; font-size:14px; font-weight:800; text-decoration:none;
  transition:all 0.3s ease;
}
.faq-cta-btn:hover { box-shadow:0 8px 25px rgba(232,40,42,0.45); transform:translateY(-2px); }
</style>

<!-- HERO -->
<section class="faq-hero">
  <div class="container">
    <p class="kicker">FAQ &bull; Questions fréquentes</p>
    <h1>Vos questions, <span>nos réponses claires.</span></h1>
    <p class="desc">Tout ce que vous devez savoir sur nos services immobiliers, BTP, financement, gestion locative et accompagnement diaspora.</p>
  </div>
</section>

<!-- FAQ ACCORDION -->
<section class="faq-section">
  <div class="container">

    <p class="faq-category-title"><i class="fa-solid fa-circle-info"></i> Questions générales</p>

    <div class="faq-item">
      <div class="faq-question">C'est quoi IBIG IMMO TRUST ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>IBIG IMMO TRUST est la branche Immobilier & BTP de INTERMARK BUSINESS INTERNATIONAL GROUP SARL, spécialisée dans la construction, la rénovation, les chantiers inachevés, la gestion locative et le financement immobilier en Côte d'Ivoire.</p></div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Dans quelles zones intervenez-vous ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Nous intervenons à Abidjan et dans plusieurs villes de Côte d'Ivoire. Nous accompagnons également les projets initiés par la diaspora depuis l'Europe, les USA, le Canada et d'autres pays.</p></div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Quels types de biens prenez-vous en charge ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Terrains, maisons, appartements, immeubles, locaux professionnels, bureaux, chantiers inachevés et projets de construction neuve.</p></div>
    </div>

    <p class="faq-category-title"><i class="fa-solid fa-coins"></i> Financement & modèles économiques</p>

    <div class="faq-item">
      <div class="faq-question">Quel est le modèle Direct IBIG ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>C'est un modèle sans avance où IBIG préfinance la finition ou la construction. Le remboursement se fait via les loyers sur une durée de 3 à 7 ans.</p></div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Comment fonctionne le financement par investisseurs agréés ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Le propriétaire apporte 10 à 20% du budget. Des investisseurs privés agréés complètent le financement et se remboursent progressivement sur les loyers générés.</p></div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Puis-je financer mon projet avec une banque ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Oui. Nous collaborons avec des banques et microfinances partenaires, montons le dossier complet et supervisons le chantier pour sécuriser les décaissements.</p></div>
    </div>

    <p class="faq-category-title"><i class="fa-solid fa-key"></i> Gestion locative & propriétaires</p>

    <div class="faq-item">
      <div class="faq-question">Offrez-vous le loyer garanti ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Oui, selon les termes du contrat. Le propriétaire reçoit son loyer au plus tard le 10 du mois, même en cas de retard du locataire (conditions définies dans le mandat de gestion).</p></div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Comment se passe la mise en location ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Photos professionnelles, diffusion multicanal, organisation des visites, contrôle de solvabilité, signature du bail, gestion quotidienne et maintenance du bien.</p></div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Puis-je confier un bien à distance (diaspora) ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Oui, grâce à notre système de suivi digital : photos, vidéos, visites virtuelles, rapports réguliers. Idéal pour la diaspora résidant en Europe, USA ou Canada.</p></div>
    </div>

    <p class="faq-category-title"><i class="fa-solid fa-helmet-safety"></i> Chantiers & Construction</p>

    <div class="faq-item">
      <div class="faq-question">Pouvez-vous terminer un chantier abandonné ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Oui. Nous analysons l'état du chantier, identifions et corrigeons les malfaçons, puis terminons les travaux avec un suivi technique strict et documenté.</p></div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Proposez-vous la construction clé en main ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Oui. De la fondation à la finition : planification, construction, supervision technique et livraison prête à habiter ou à exploiter.</p></div>
    </div>

    <p class="faq-category-title"><i class="fa-solid fa-file-shield"></i> Assistance foncière</p>

    <div class="faq-item">
      <div class="faq-question">Pouvez-vous vérifier un titre foncier ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Oui, nous effectuons les vérifications auprès des services compétents : authenticité des documents, morcellement, duplicatas, état cadastral et historique du bien.</p></div>
    </div>

    <div class="faq-item">
      <div class="faq-question">Aidez-vous à sécuriser un achat de terrain ?<span class="faq-icon"><i class="fa-solid fa-plus"></i></span></div>
      <div class="faq-answer"><p>Oui : recherche d'historique, validation des documents, accompagnement notarial et conseil complet avant toute signature d'acte de vente.</p></div>
    </div>

  </div>
</section>

<!-- CTA -->
<section class="faq-cta">
  <div class="container">
    <h2>Vous n'avez pas trouvé votre réponse ?</h2>
    <p>Notre équipe est disponible pour vous accompagner personnellement.</p>
    <a href="<?= BASE_URL ?>/contact.php" class="faq-cta-btn">
      <i class="fa-solid fa-paper-plane"></i> Nous contacter
    </a>
  </div>
</section>

<script>
document.querySelectorAll('.faq-question').forEach(q => {
  q.addEventListener('click', () => {
    const item = q.parentElement;
    document.querySelectorAll('.faq-item.open').forEach(o => { if(o!==item) o.classList.remove('open'); });
    item.classList.toggle('open');
  });
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>