@extends('layouts.admin')
@section('title', 'Manage Settings')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
  --s-surface:   #1a1d27;
  --s-surface2:  #222636;
  --s-border:    rgba(255,255,255,.07);
  --s-accent:    #6366f1;
  --s-accent2:   #818cf8;
  --s-green:     #10b981;
  --s-green2:    #34d399;
  --s-success:   #22c55e;
  --s-danger:    #ef4444;
  --s-warning:   #f59e0b;
  --s-info:      #38bdf8;
  --s-text:      #e2e8f0;
  --s-muted:     #64748b;
  --s-radius:    14px;
  --s-radius-sm: 8px;
  --s-shadow:    0 8px 32px rgba(0,0,0,.45);
}

.s-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--s-text); }

/* hero header */
.s-hero {
  background:linear-gradient(135deg,#0f0f1a 0%,#1a1040 50%,#1e1650 100%);
  border-radius:var(--s-radius); padding:40px 36px;
  margin-bottom:32px; position:relative; overflow:hidden; box-shadow:var(--s-shadow);
}
.s-hero::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.04'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z'/%3E%3C/g%3E%3C/svg%3E");
}
.s-hero::after {
  content:''; position:absolute; right:-60px; bottom:-60px;
  width:260px; height:260px; border-radius:50%;
  background:radial-gradient(circle,rgba(99,102,241,.15) 0%,transparent 70%);
}
.s-hero-title {
  font-size:1.7rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,var(--s-accent2));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
  margin-bottom:6px;
}
.s-hero-sub { color:rgba(255,255,255,.4); font-size:.88rem; position:relative; z-index:1; }
.s-hero-icon {
  position:absolute; right:36px; top:50%; transform:translateY(-50%);
  font-size:5rem; opacity:.06; z-index:0;
}

/* setting cards grid */
.s-cards-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
@media(max-width:640px){ .s-cards-grid { grid-template-columns:1fr; } }

.s-setting-card {
  background:var(--s-surface); border:1px solid var(--s-border);
  border-radius:var(--s-radius); padding:32px 28px;
  text-align:center; position:relative; overflow:hidden;
  transition:transform .2s, border-color .2s, box-shadow .2s;
  box-shadow:var(--s-shadow); cursor:pointer;
}
.s-setting-card:hover { transform:translateY(-4px); box-shadow:0 16px 48px rgba(0,0,0,.5); }
.s-setting-card.general { border-top:3px solid var(--s-accent); }
.s-setting-card.general:hover { border-color:var(--s-accent2); }
.s-setting-card.payment { border-top:3px solid var(--s-green); }
.s-setting-card.payment:hover { border-color:var(--s-green2); }

/* glow effect */
.s-setting-card::before {
  content:''; position:absolute; top:0; left:0; right:0; height:100px;
  opacity:0; transition:opacity .3s;
}
.s-setting-card.general::before { background:radial-gradient(ellipse at 50% 0%, rgba(99,102,241,.1) 0%,transparent 70%); }
.s-setting-card.payment::before { background:radial-gradient(ellipse at 50% 0%, rgba(16,185,129,.1) 0%,transparent 70%); }
.s-setting-card:hover::before { opacity:1; }

/* card icon */
.s-card-icon {
  width:72px; height:72px; border-radius:20px;
  display:flex; align-items:center; justify-content:center;
  font-size:1.6rem; margin:0 auto 20px;
}
.s-setting-card.general .s-card-icon { background:rgba(99,102,241,.12); color:var(--s-accent2); border:1px solid rgba(99,102,241,.2); }
.s-setting-card.payment .s-card-icon { background:rgba(16,185,129,.12); color:var(--s-green2); border:1px solid rgba(16,185,129,.2); }

.s-card-title { font-size:1.15rem; font-weight:700; margin-bottom:8px; }
.s-card-desc  { color:var(--s-muted); font-size:.875rem; margin-bottom:24px; line-height:1.5; }

/* quick info row */
.s-quick-info { display:flex; justify-content:center; gap:16px; margin-bottom:24px; flex-wrap:wrap; }
.s-quick-item { text-align:center; }
.s-quick-val  { font-size:.8rem; font-weight:700; }
.s-quick-lbl  { font-size:.68rem; color:var(--s-muted); }

/* open button */
.s-open-btn {
  display:inline-flex; align-items:center; justify-content:center; gap:8px;
  padding:11px 28px; border-radius:var(--s-radius-sm);
  font-family:inherit; font-size:.875rem; font-weight:700;
  cursor:pointer; border:none; transition:all .2s; width:100%;
}
.s-setting-card.general .s-open-btn { background:var(--s-accent); color:#fff; }
.s-setting-card.general .s-open-btn:hover { background:var(--s-accent2); box-shadow:0 4px 16px rgba(99,102,241,.4); transform:translateY(-1px); }
.s-setting-card.payment .s-open-btn { background:var(--s-green); color:#022c22; }
.s-setting-card.payment .s-open-btn:hover { background:var(--s-green2); box-shadow:0 4px 16px rgba(16,185,129,.4); transform:translateY(-1px); }

/* MODAL */
.s-modal-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.72); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.s-modal-overlay.open { opacity:1; pointer-events:auto; }
.s-modal {
  background:var(--s-surface); border:1px solid var(--s-border);
  border-radius:18px; width:min(580px,96vw); max-height:90vh; overflow-y:auto;
  box-shadow:0 24px 64px rgba(0,0,0,.6);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.s-modal-overlay.open .s-modal { transform:translateY(0) scale(1); }
.s-modal-header {
  padding:22px 28px 18px; border-bottom:1px solid var(--s-border);
  display:flex; align-items:center; justify-content:space-between;
}
.s-modal-title { font-size:1.1rem; font-weight:700; display:flex; align-items:center; gap:10px; }
.s-modal-close {
  background:var(--s-surface2); border:1px solid var(--s-border);
  color:var(--s-muted); width:32px; height:32px; border-radius:8px;
  cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s;
}
.s-modal-close:hover { color:var(--s-text); }
.s-modal-body   { padding:24px 28px; }
.s-modal-footer { padding:18px 28px; border-top:1px solid var(--s-border); display:flex; gap:10px; justify-content:flex-end; }

/* form fields */
.s-field { margin-bottom:18px; }
.s-field label { display:block; font-size:.8rem; font-weight:600; color:var(--s-muted); margin-bottom:7px; text-transform:uppercase; letter-spacing:.06em; }
.s-field input,.s-field select,.s-field textarea {
  width:100%; background:var(--s-surface2); border:1px solid var(--s-border);
  border-radius:var(--s-radius-sm); padding:10px 14px; color:var(--s-text);
  font-family:inherit; font-size:.875rem; outline:none;
  transition:border-color .2s,box-shadow .2s; resize:vertical;
}
.s-field input:focus,.s-field select:focus,.s-field textarea:focus {
  border-color:var(--s-accent); box-shadow:0 0 0 3px rgba(99,102,241,.12);
}
.s-field .err { color:#fca5a5; font-size:.78rem; margin-top:5px; }
.s-field input[type="file"] { padding:8px 12px; cursor:pointer; }
.s-field .preview-wrap { margin-top:10px; }
.s-field .preview-wrap img { height:40px; border-radius:6px; object-fit:contain; background:var(--s-surface2); padding:4px 8px; border:1px solid var(--s-border); }
.s-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:0 18px; }
.s-section-title { font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--s-accent2); margin:4px 0 16px; display:flex; align-items:center; gap:8px; }
.s-section-title::after { content:''; flex:1; height:1px; background:var(--s-border); }
.s-section-title.green { color:var(--s-green2); }

/* payment method toggle */
.s-pay-method {
  background:var(--s-surface2); border:1px solid var(--s-border);
  border-radius:var(--s-radius-sm); padding:14px 16px;
  display:flex; align-items:center; justify-content:space-between;
  margin-bottom:12px; transition:border-color .2s;
}
.s-pay-method:hover { border-color:rgba(255,255,255,.12); }
.s-pay-method-left { display:flex; align-items:center; gap:12px; }
.s-pay-method-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; }
.s-pay-method-name { font-weight:600; font-size:.875rem; }
.s-pay-method-desc { font-size:.75rem; color:var(--s-muted); margin-top:2px; }
.s-toggle-wrap select {
  background:var(--s-surface); border:1px solid var(--s-border);
  border-radius:6px; padding:5px 10px; color:var(--s-text);
  font-family:inherit; font-size:.78rem; outline:none;
}

/* phone input with flag */
.s-phone-wrap { display:flex; align-items:center; gap:0; }
.s-phone-prefix { background:var(--s-surface2); border:1px solid var(--s-border); border-right:none; padding:10px 12px; border-radius:var(--s-radius-sm) 0 0 var(--s-radius-sm); font-size:.8rem; color:var(--s-muted); white-space:nowrap; }
.s-phone-wrap input { border-radius:0 var(--s-radius-sm) var(--s-radius-sm) 0 !important; }

/* btn */
.s-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--s-radius-sm); transition:all .2s; }
.s-btn-primary { background:var(--s-accent); color:#fff; padding:10px 20px; font-size:.875rem; }
.s-btn-primary:hover { background:var(--s-accent2); transform:translateY(-1px); }
.s-btn-green { background:var(--s-green); color:#022c22; padding:10px 20px; font-size:.875rem; }
.s-btn-green:hover { background:var(--s-green2); transform:translateY(-1px); }
.s-btn-outline { background:transparent; color:var(--s-text); border:1px solid var(--s-border); padding:10px 16px; font-size:.875rem; }
.s-btn-outline:hover { background:var(--s-surface2); color:var(--s-text); }

/* toast */
#s-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.s-toast { display:flex; align-items:center; gap:12px; background:var(--s-surface); border:1px solid var(--s-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.s-toast.show { transform:translateX(0); }
.s-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.95rem; }
.s-toast-success .s-toast-icon { background:rgba(34,197,94,.15); color:var(--s-success); }
.s-toast-danger  .s-toast-icon { background:rgba(239,68,68,.15);  color:var(--s-danger); }
.s-toast-title { font-size:.875rem; font-weight:700; color:var(--s-text); }
.s-toast-msg   { font-size:.78rem;  color:var(--s-muted); margin-top:2px; }
.s-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:sBar 3.5s linear forwards; }
.s-toast-success .s-toast-bar { background:var(--s-success); }
.s-toast-danger  .s-toast-bar { background:var(--s-danger); }
@keyframes sBar { from{width:100%} to{width:0%} }
.s-modal::-webkit-scrollbar { width:5px; }
.s-modal::-webkit-scrollbar-thumb { background:var(--s-border); border-radius:10px; }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="s-wrap">

  {{-- Hero --}}
  <div class="s-hero">
    <i class="fas fa-cogs s-hero-icon"></i>
    <div class="s-hero-title"><i class="fas fa-cogs me-2"></i>System Settings</div>
    <div class="s-hero-sub">Configure your website preferences, payment gateways and more</div>
  </div>

  {{-- Setting Cards --}}
  <div class="s-cards-grid">

    {{-- General Settings Card --}}
    <div class="s-setting-card general" onclick="openGeneralModal()">
      <div class="s-card-icon"><i class="fas fa-globe"></i></div>
      <div class="s-card-title">General Settings</div>
      <div class="s-card-desc">Configure your website name, contact information, address and logo</div>

      <div class="s-quick-info">
        <div class="s-quick-item">
          <div class="s-quick-val" style="color:var(--s-accent2);">{{ $settings['site_name'] ?? 'Not Set' }}</div>
          <div class="s-quick-lbl">Site Name</div>
        </div>
        <div class="s-quick-item">
          <div class="s-quick-val" style="color:var(--s-accent2);">{{ $settings['site_email'] ?? 'Not Set' }}</div>
          <div class="s-quick-lbl">Email</div>
        </div>
      </div>

      <button class="s-open-btn" type="button">
        <i class="fas fa-sliders-h"></i> Open General Settings
      </button>
    </div>

    {{-- Payment Settings Card --}}
    <div class="s-setting-card payment" onclick="openPaymentModal()">
      <div class="s-card-icon"><i class="fas fa-credit-card"></i></div>
      <div class="s-card-title">Payment Settings</div>
      <div class="s-card-desc">Manage payment gateways, mobile banking numbers and online payment options</div>

      <div class="s-quick-info">
        <div class="s-quick-item">
          <div class="s-quick-val" style="color:var(--s-green2);">
            @if($settings['bkash_number'] ?? false)
              <i class="fas fa-check-circle"></i> bKash
            @else
              <i class="fas fa-times-circle" style="color:var(--s-muted);"></i> bKash
            @endif
          </div>
          <div class="s-quick-lbl">Mobile Pay</div>
        </div>
        <div class="s-quick-item">
          <div class="s-quick-val">
            @if(($settings['stripe_status'] ?? 0) == 1)
              <span style="color:var(--s-green2);"><i class="fas fa-check-circle"></i> Stripe</span>
            @else
              <span style="color:var(--s-muted);"><i class="fas fa-times-circle"></i> Stripe</span>
            @endif
          </div>
          <div class="s-quick-lbl">Online Pay</div>
        </div>
      </div>

      <button class="s-open-btn" type="button">
        <i class="fas fa-credit-card"></i> Open Payment Settings
      </button>
    </div>

  </div>

</div>

{{-- ═══ GENERAL SETTINGS MODAL ═══ --}}
<div class="s-modal-overlay" id="generalModal">
  <div class="s-modal">
    <div class="s-modal-header">
      <div class="s-modal-title">
        <div style="width:34px;height:34px;border-radius:10px;background:rgba(99,102,241,.15);display:flex;align-items:center;justify-content:center;color:var(--s-accent2);">
          <i class="fas fa-globe" style="font-size:.85rem;"></i>
        </div>
        General Settings
      </div>
      <button class="s-modal-close" onclick="closeModal('generalModal')"><i class="fas fa-times"></i></button>
    </div>

    <form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data">
      @csrf
      <div class="s-modal-body">

        <div class="s-section-title"><i class="fas fa-info-circle"></i> Site Identity</div>
        <div class="s-field">
          <label>Site Name</label>
          <input type="text" name="site_name" value="{{ $settings['site_name'] ?? '' }}" placeholder="My Tour Website">
          @error('site_name')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="s-section-title"><i class="fas fa-address-book"></i> Contact Info</div>
        <div class="s-grid-2">
          <div class="s-field">
            <label>Email Address</label>
            <input type="email" name="site_email" value="{{ $settings['site_email'] ?? '' }}" placeholder="info@example.com">
            @error('site_email')<div class="err">{{ $message }}</div>@enderror
          </div>
          <div class="s-field">
            <label>Phone Number</label>
            <input type="text" name="phone" value="{{ $settings['phone'] ?? '' }}" placeholder="+880 1xxx-xxxxxx">
            @error('phone')<div class="err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="s-field">
          <label>Address</label>
          <textarea name="address" rows="2" placeholder="Full business address...">{{ $settings['address'] ?? '' }}</textarea>
        </div>

        <div class="s-section-title"><i class="fas fa-image"></i> Branding</div>
        <div class="s-field">
          <label>Site Logo</label>
          <input type="file" name="logo" accept="image/*" onchange="previewLogo(this)">
          @error('logo')<div class="err">{{ $message }}</div>@enderror
          <div class="preview-wrap">
            @if($settings['logo'] ?? false)
              <img id="logo-preview" src="{{ asset('uploads/settings/'.$settings['logo']) }}" alt="Logo">
            @else
              <img id="logo-preview" style="display:none;">
            @endif
          </div>
        </div>

      </div>
      <div class="s-modal-footer">
        <button type="button" class="s-btn s-btn-outline" onclick="closeModal('generalModal')">Cancel</button>
        <button type="submit" class="s-btn s-btn-primary"><i class="fas fa-save"></i> Save Settings</button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ PAYMENT SETTINGS MODAL ═══ --}}
<div class="s-modal-overlay" id="paymentModal">
  <div class="s-modal">
    <div class="s-modal-header">
      <div class="s-modal-title">
        <div style="width:34px;height:34px;border-radius:10px;background:rgba(16,185,129,.15);display:flex;align-items:center;justify-content:center;color:var(--s-green2);">
          <i class="fas fa-credit-card" style="font-size:.85rem;"></i>
        </div>
        Payment Settings
      </div>
      <button class="s-modal-close" onclick="closeModal('paymentModal')"><i class="fas fa-times"></i></button>
    </div>

    <form method="POST" action="{{ route('admin.settings.payment.update') }}">
      @csrf
      <div class="s-modal-body">

        <div class="s-section-title green"><i class="fas fa-mobile-alt"></i> Mobile Banking</div>

        {{-- bKash --}}
        <div class="s-pay-method">
          <div class="s-pay-method-left">
            <div class="s-pay-method-icon" style="background:rgba(231,0,116,.12);color:#f43f8e;">
              <i class="fas fa-mobile-alt"></i>
            </div>
            <div>
              <div class="s-pay-method-name">bKash</div>
              <div class="s-pay-method-desc">Mobile financial service</div>
            </div>
          </div>
          <div style="flex:1;margin-left:16px;">
            <input type="text" name="bkash_number"
              value="{{ $settings['bkash_number'] ?? '' }}"
              placeholder="01XXXXXXXXX"
              style="background:var(--s-surface);border:1px solid var(--s-border);border-radius:6px;padding:7px 12px;color:var(--s-text);font-family:'JetBrains Mono',monospace;font-size:.82rem;outline:none;width:100%;">
          </div>
        </div>

        {{-- Nagad --}}
        <div class="s-pay-method">
          <div class="s-pay-method-left">
            <div class="s-pay-method-icon" style="background:rgba(249,115,22,.12);color:#fb923c;">
              <i class="fas fa-mobile-alt"></i>
            </div>
            <div>
              <div class="s-pay-method-name">Nagad</div>
              <div class="s-pay-method-desc">Digital financial service</div>
            </div>
          </div>
          <div style="flex:1;margin-left:16px;">
            <input type="text" name="nagad_number"
              value="{{ $settings['nagad_number'] ?? '' }}"
              placeholder="01XXXXXXXXX"
              style="background:var(--s-surface);border:1px solid var(--s-border);border-radius:6px;padding:7px 12px;color:var(--s-text);font-family:'JetBrains Mono',monospace;font-size:.82rem;outline:none;width:100%;">
          </div>
        </div>

        <div class="s-section-title green" style="margin-top:20px;"><i class="fas fa-globe"></i> Online Payment Gateways</div>

        {{-- Stripe --}}
        <div class="s-pay-method">
          <div class="s-pay-method-left">
            <div class="s-pay-method-icon" style="background:rgba(99,91,255,.12);color:#818cf8;">
              <i class="fab fa-stripe-s"></i>
            </div>
            <div>
              <div class="s-pay-method-name">Stripe</div>
              <div class="s-pay-method-desc">International card payments</div>
            </div>
          </div>
          <div class="s-toggle-wrap">
            <select name="stripe_status">
              <option value="1" {{ ($settings['stripe_status'] ?? 0) == 1 ? 'selected' : '' }}>✅ Active</option>
              <option value="0" {{ ($settings['stripe_status'] ?? 0) == 0 ? 'selected' : '' }}>❌ Inactive</option>
            </select>
          </div>
        </div>

        {{-- PayPal --}}
        <div class="s-pay-method">
          <div class="s-pay-method-left">
            <div class="s-pay-method-icon" style="background:rgba(0,112,240,.12);color:#60a5fa;">
              <i class="fab fa-paypal"></i>
            </div>
            <div>
              <div class="s-pay-method-name">PayPal</div>
              <div class="s-pay-method-desc">Global online payments</div>
            </div>
          </div>
          <div class="s-toggle-wrap">
            <select name="paypal_status">
              <option value="1" {{ ($settings['paypal_status'] ?? 0) == 1 ? 'selected' : '' }}>✅ Active</option>
              <option value="0" {{ ($settings['paypal_status'] ?? 0) == 0 ? 'selected' : '' }}>❌ Inactive</option>
            </select>
          </div>
        </div>

      </div>
      <div class="s-modal-footer">
        <button type="button" class="s-btn s-btn-outline" onclick="closeModal('paymentModal')">Cancel</button>
        <button type="submit" class="s-btn s-btn-green"><i class="fas fa-save"></i> Save Payment Settings</button>
      </div>
    </form>
  </div>
</div>

<div id="s-toast-container"></div>

<script>
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }

document.querySelectorAll('.s-modal-overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown', e => {
  if(e.key==='Escape') document.querySelectorAll('.s-modal-overlay.open').forEach(el=>closeModal(el.id));
});

function openGeneralModal() { openModal('generalModal'); }
function openPaymentModal() { openModal('paymentModal'); }

// prevent card click bubbling from button
document.querySelectorAll('.s-open-btn').forEach(btn => {
  btn.addEventListener('click', e => e.stopPropagation());
});

function previewLogo(input) {
  var p = document.getElementById('logo-preview');
  if(input.files && input.files[0]) {
    var r = new FileReader();
    r.onload = e => { p.src = e.target.result; p.style.display='block'; };
    r.readAsDataURL(input.files[0]);
  }
}

// reopen modal on validation error
@if($errors->any())
  @if(old('site_name') !== null || old('site_email') !== null)
    openGeneralModal();
  @elseif(old('bkash_number') !== null || old('stripe_status') !== null)
    openPaymentModal();
  @endif
@endif

function showToast(type, title, msg) {
  var icons = {success:'fas fa-check-circle', danger:'fas fa-exclamation-circle'};
  var c = document.getElementById('s-toast-container');
  var t = document.createElement('div');
  t.className = 's-toast s-toast-' + type;
  t.innerHTML = `<div class="s-toast-icon"><i class="${icons[type]}"></i></div>
    <div><div class="s-toast-title">${title}</div><div class="s-toast-msg">${msg}</div></div>
    <span class="s-toast-bar"></span>`;
  c.appendChild(t);
  setTimeout(()=>t.classList.add('show'),20);
  setTimeout(()=>{ t.classList.remove('show'); setTimeout(()=>t.remove(),400); },3500);
}
(function(){
  var s=document.getElementById('flash-success');
  var e=document.getElementById('flash-error');
  if(s) showToast('success','Success',s.dataset.msg);
  if(e) showToast('danger','Error',e.dataset.msg);
})();
</script>

@endsection