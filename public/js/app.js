// KasirCendana — Main JS

// ── SIDEBAR TOGGLE ──────────────────────────────────────────────
const sidebar = document.getElementById("sidebar");
const menuToggle = document.getElementById("menuToggle");
const sidebarOverlay = document.getElementById("sidebarOverlay");

if (menuToggle) {
    menuToggle.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        if (sidebarOverlay)
            sidebarOverlay.style.display = sidebar.classList.contains("open")
                ? "block"
                : "none";
    });
}
if (sidebarOverlay) {
    sidebarOverlay.addEventListener("click", () => {
        sidebar.classList.remove("open");
        sidebarOverlay.style.display = "none";
    });
}

// ── MODAL HELPERS ───────────────────────────────────────────────
function openModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add("show");
}

function closeModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.remove("show");
}

document.querySelectorAll("[data-modal-close]").forEach((btn) => {
    btn.addEventListener("click", () => {
        const target =
            btn.dataset.modalClose || btn.closest(".modal-backdrop")?.id;
        if (target) closeModal(target);
    });
});

document.querySelectorAll(".modal-backdrop").forEach((m) => {
    m.addEventListener("click", (e) => {
        if (e.target === m) m.classList.remove("show");
    });
});

// ── FAQ ACCORDION ───────────────────────────────────────────────
document.querySelectorAll(".faq-question").forEach((q) => {
    q.addEventListener("click", () => {
        const answer = q.nextElementSibling;
        const isOpen = answer.classList.contains("show");
        document
            .querySelectorAll(".faq-answer.show")
            .forEach((a) => a.classList.remove("show"));
        document
            .querySelectorAll(".faq-question.open")
            .forEach((a) => a.classList.remove("open"));
        if (!isOpen) {
            answer.classList.add("show");
            q.classList.add("open");
        }
    });
});

// ── FAQ FLOATING BUTTON ─────────────────────────────────────────
const faqBtn = document.getElementById("faqBtn");
if (faqBtn) {
    faqBtn.addEventListener("click", () => openModal("modalFAQ"));
}

// ── AUTO DISMISS ALERTS ─────────────────────────────────────────
document.querySelectorAll(".alert-auto").forEach((alert) => {
    setTimeout(() => {
        alert.style.opacity = "0";
        alert.style.transition = "opacity .4s";
        setTimeout(() => alert.remove(), 400);
    }, 3500);
});

// ── TRANSAKSI: DYNAMIC PRODUCT ROWS ─────────────────────────────
let rowCount = 0;

function formatRupiah(num) {
    return "Rp " + Number(num).toLocaleString("id-ID");
}

function hitungTotal() {
    let total = 0;
    document.querySelectorAll(".row-subtotal").forEach((el) => {
        total += parseFloat(el.dataset.subtotal || 0);
    });

    const diskonPersen = parseFloat(
        document.getElementById("diskon_persen")?.value || 0,
    );
    const nominalEl = document.getElementById("diskon_nominal");
    let diskon = parseFloat(nominalEl?.value || 0);

    if (diskonPersen > 0) {
        diskon = total * (diskonPersen / 100);
        if (nominalEl) nominalEl.value = diskon.toFixed(0);
    }

    const totalAkhir = Math.max(0, total - diskon);
    const el = document.getElementById("total-display");
    if (el) el.textContent = formatRupiah(totalAkhir);
}

function tambahProdukRow(produkList, selectedId = null, selectedJumlah = 1) {
    rowCount++;
    const container = document.getElementById("produk-container");
    if (!container) return;

    const row = document.createElement("div");
    row.className = "produk-row";
    row.id = "row-" + rowCount;

    let options = '<option value="">-- Pilih Produk --</option>';
    produkList.forEach((p) => {
        const sel = selectedId && p.id == selectedId ? "selected" : "";
        options += `<option value="${p.id}" data-harga="${p.harga}" data-stok="${p.stok}" ${sel}>${p.nama} (Stok: ${p.stok})</option>`;
    });

    row.innerHTML = `
        <select class="form-control produk-select" name="produk[${rowCount}][ProdukID]" required onchange="updateSubtotal(${rowCount})">
            ${options}
        </select>
        <input type="number" class="form-control" name="produk[${rowCount}][jumlah]"
            id="jumlah-${rowCount}" min="1" value="${selectedJumlah}" style="width:80px"
            onchange="updateSubtotal(${rowCount})" oninput="updateSubtotal(${rowCount})">
        <span class="row-subtotal text-bold" id="subtotal-${rowCount}" data-subtotal="0" style="min-width:120px; text-align:right">Rp 0</span>
        <button type="button" class="btn btn-danger btn-sm" onclick="hapusProdukRow(${rowCount})">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6m4-6v6"/><path d="M9 6V4h6v2"/></svg>
        </button>
    `;

    container.appendChild(row);

    // Hitung subtotal awal jika ada selected
    if (selectedId) {
        updateSubtotal(rowCount);
    }
}

function hapusProdukRow(id) {
    const row = document.getElementById("row-" + id);
    if (row) row.remove();
    hitungTotal();
}

function updateSubtotal(id) {
    const select = document.querySelector(`#row-${id} .produk-select`);
    const jumlahEl = document.getElementById("jumlah-" + id);
    const subtotalEl = document.getElementById("subtotal-" + id);
    if (!select || !jumlahEl || !subtotalEl) return;

    const opt = select.options[select.selectedIndex];
    const harga = parseFloat(opt?.dataset?.harga || 0);
    const stok = parseInt(opt?.dataset?.stok || 0);
    let jumlah = parseInt(jumlahEl.value || 1);

    if (jumlah > stok) {
        jumlah = stok;
        jumlahEl.value = stok;
    }

    const sub = harga * jumlah;
    subtotalEl.textContent = formatRupiah(sub);
    subtotalEl.dataset.subtotal = sub;
    hitungTotal();
}

// diskon persen to nominal
const diskonPersenEl = document.getElementById("diskon_persen");
if (diskonPersenEl) {
    diskonPersenEl.addEventListener("input", hitungTotal);
}

const diskonNominalEl = document.getElementById("diskon_nominal");
if (diskonNominalEl) {
    diskonNominalEl.addEventListener("input", () => {
        const p = document.getElementById("diskon_persen");
        if (p) p.value = "";
        hitungTotal();
    });
}
