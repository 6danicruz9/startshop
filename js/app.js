(() => {
  'use strict';
  const root = document.documentElement;
  const savedTheme = localStorage.getItem('ss_theme') || 'light';
  root.setAttribute('data-theme', savedTheme);

  document.addEventListener('click', (e) => {
    if (e.target.closest('[data-action="toggle-theme"]')) {
      const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      root.setAttribute('data-theme', next);
      localStorage.setItem('ss_theme', next);
    }
  });

  const drawer = document.getElementById('ss-cart-drawer');
  const backdrop = document.getElementById('ss-drawer-backdrop');
  const openCart = () => { if (drawer && backdrop) { drawer.classList.add('is-open'); backdrop.classList.add('is-open'); } };
  const closeCart = () => { if (drawer && backdrop) { drawer.classList.remove('is-open'); backdrop.classList.remove('is-open'); } };

  document.addEventListener('click', (e) => {
    if (e.target.closest('[data-action="open-cart"]')) { e.preventDefault(); openCart(); }
    if (e.target.closest('[data-action="close-cart"]') || e.target === backdrop) closeCart();
  });

  async function mutateCart(payload) {
    const res = await fetch('api/cart.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
    const data = await res.json();
    if (data.ok) {
      document.querySelectorAll('[data-cart-count]').forEach(el => el.textContent = data.total_items);
      const body = document.getElementById('ss-drawer-body');
      if (body) body.innerHTML = data.drawer_html;
      const sub = document.getElementById('ss-drawer-subtotal');
      if (sub) sub.textContent = data.subtotal_formatted;
    }
    return data;
  }

  document.addEventListener('submit', async (e) => {
    const form = e.target.closest('[data-form="add-to-cart"]');
    if (!form) return;
    e.preventDefault();
    const fd = new FormData(form);
    const res = await mutateCart({
      action: 'add',
      product_id: Number(fd.get('product_id')),
      variant_id: fd.get('variant_id') ? Number(fd.get('variant_id')) : null,
      quantity: Number(fd.get('quantity') || 1)
    });
    if (res.ok) openCart();
  });

  document.addEventListener('click', async (e) => {
    const qtyBtn = e.target.closest('[data-cart-qty]');
    if (qtyBtn) await mutateCart({ action: 'delta', item_key: qtyBtn.getAttribute('data-key'), delta: Number(qtyBtn.getAttribute('data-cart-qty')) });
    const remBtn = e.target.closest('[data-cart-remove]');
    if (remBtn) await mutateCart({ action: 'remove', item_key: remBtn.getAttribute('data-cart-remove') });
    const wishBtn = e.target.closest('[data-action="toggle-wishlist"]');
    if (wishBtn) {
      e.preventDefault();
      const r = await fetch('api/wishlist.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ product_id: Number(wishBtn.getAttribute('data-product-id')) }) });
      const d = await r.json();
      if (d.ok) wishBtn.classList.toggle('is-active', d.in_wishlist);
    }
  });

  const searchInput = document.getElementById('ss-live-search');
  const searchDropdown = document.getElementById('ss-search-dropdown');
  if (searchInput && searchDropdown) {
    searchInput.addEventListener('input', async () => {
      const q = searchInput.value.trim();
      if (q.length < 2) { searchDropdown.classList.remove('is-open'); return; }
      const r = await fetch('api/search.php?q=' + encodeURIComponent(q));
      const d = await r.json();
      searchDropdown.innerHTML = (d.items || []).map(i => `
        <a href="?route=product&slug=${i.slug}" style="display:flex;gap:10px;padding:10px;text-decoration:none;color:var(--text-primary);border-bottom:1px solid var(--border-subtle);align-items:center;">
          <img src="${i.image}" style="width:40px;height:50px;object-fit:cover;">
          <div style="flex:1;font-size:13px;font-weight:600;">${i.name}</div>
          <div style="font-size:12px;">${i.price_formatted}</div>
        </a>
      `).join('') || '<div style="padding:12px;font-size:12px;">Sin resultados</div>';
      searchDropdown.classList.add('is-open');
    });
  }
})();