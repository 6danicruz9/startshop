  <footer style="border-top:1px solid var(--border-subtle);margin-top:4rem;background:var(--bg-elevated);">
    <div class="ss-Container" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:2rem;">
      <div>
        <div class="ss-Brand" style="margin-bottom:10px;">STARTSHOP ATELIER</div>
        <p style="color:var(--text-secondary);font-size:0.86rem;">Ingeniería textil sobre arquitectura nativa PHP + MySQL StartShop.</p>
      </div>
      <div>
        <h4 style="font-family:var(--font-mono);font-size:0.75rem;">NAVEGACIÓN</h4>
        <ul style="list-style:none;padding:0;display:grid;gap:8px;font-size:0.86rem;">
          <li><a href="?route=catalog" style="color:var(--text-secondary);text-decoration:none;">Colección Completa</a></li>
          <li><a href="?route=cms&page=about" style="color:var(--text-secondary);text-decoration:none;">Sobre el Atelier</a></li>
          <li><a href="?route=cms&page=faq" style="color:var(--text-secondary);text-decoration:none;">Preguntas Frecuentes</a></li>
        </ul>
      </div>
      <div>
        <h4 style="font-family:var(--font-mono);font-size:0.75rem;"><?= e(__('footer.newsletter_title')) ?></h4>
        <form action="api/newsletter.php" method="POST" style="display:flex;gap:8px;">
          <input type="email" name="email" required placeholder="tu@email.com" class="ss-SearchInput">
          <button type="submit" class="ss-Btn">OK</button>
        </form>
      </div>
    </div>
  </footer>
  <?php require __DIR__ . '/cart-drawer.php'; ?>
  <script src="js/scripts.js"></script>
  <script src="js/app.js"></script>
</body>
</html>