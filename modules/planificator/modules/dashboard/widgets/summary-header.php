
<!-- Dashboard Summary -->
<div class="alert alert-info">
    Tableau de bord financier
    <?php if ($currentMembre): ?>
        - <?php echo htmlspecialchars($currentMembre['prenom'] . ' ' . $currentMembre['nom']); ?>
    <?php endif; ?>
</div>