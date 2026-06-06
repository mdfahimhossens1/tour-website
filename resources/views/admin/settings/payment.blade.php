@extends('layouts.admin')
@section('title', 'Payment Settings')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --s-surface:   #1a1d27;
  --s-surface2:  #222636;
  --s-border:    rgba(255,255,255,.07);
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
.s-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--s-text); max-width:100%; margin:0 auto; }

/* hero */
.s-hero {
  background:linear-gradient(135deg,#022c22 0%,#064e3b 55%,#065f46 100%);
  border-radius:var(--s-radius); padding:28px 32px 72px;
  position:relative; overflow:hidden; box-shadow:var(--s-shadow); margin-bottom:-52px;
}
.s-hero::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%2310b981' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/svg%3E"); }
.s-hero::after { content:''; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle,rgba(16,185,129,.2) 0%,transparent 70%); }
.s-hero-nav { position:relative; z-index:1; display:flex; justify-content:space-between; align-items:center; }
.s-breadcrumb { font-size:.8rem; color:rgba(255,255,255,.45); }
.s-breadcrumb a { color:rgba(255,255,255,.45); text-decoration:none; }
.s-breadcrumb a:hover { color:rgba(255,255,255,.7); }
.s-breadcrumb span { color:rgba(255,255,255,.8); }

/* main card */
.s-main-card { background:var(--s-surface); border:1px solid var(--s-border); border-radius:var(--s-radius); box-shadow:var(--s-shadow); position:relative; z-index:2; }
.s-card-header { padding:24px 28px 20px; border-bottom:1px solid var(--s-border); display:flex; align-items:center; gap:14px; }
.s-card-icon { width:42px; height:42px; border-radius:12px; background:rgba(16,185,129,.15); border:1px solid rgba(16,185,129,.2); display:flex; align-items:center; justify-content:center; color:var(--s-green2); font-size:1rem; flex-shrink:0; }
.s-card-title { font-size:1.1rem; font-weight:700; }
.s-card-sub   { font-size:.8rem; color:var(--s-muted); margin-top:2px; }
.s-card-body  { padding:28px; }
.s-card-footer{ padding:18px 28px; border-top:1px solid var(--s-border); display:flex; gap:10px; justify-content:flex-end; }

/* section title */
.s-section { font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--s-green2); margin:0 0 16px; display:flex; align-items:center; gap:8px; }
.s-section::after { content:''; flex:1; height:1px; background:var(--s-border); }
.s-divider { height:1px; background:var(--s-border); margin:24px 0; }

/* payment method row */
.s-pay-row {
  background:var(--s-surface2); border:1px solid var(--s-border);
  border-radius:var(--s-radius-sm); padding:18px 20px;
  display:flex; align-items:center; gap:16px;
  margin-bottom:12px; transition:border-color .2s, box-shadow .2s;
}
.s-pay-row:hover { border-color:rgba(255,255,255,.12); }
.s-pay-row:last-child { margin-bottom:0; }
.s-pay-logo {
  width:46px; height:46px; border-radius:12px;
  display:flex; align-items:center; justify-content:center;
  font-size:1.1rem; flex-shrink:0;
}
.s-pay-info { flex:1; }
.s-pay-name { font-weight:700; font-size:.9rem; }
.s-pay-desc { font-size:.75rem; color:var(--s-muted); margin-top:2px; }
.s-pay-input-wrap { flex:1.5; }

/* number input */
.s-num-input {
  width:100%; background:var(--s-surface);
  border:1px solid var(--s-border); border-radius:var(--s-radius-sm);
  padding:10px 14px; color:var(--s-text);
  font-family:'JetBrains Mono',monospace; font-size:.85rem;
  outline:none; transition:border-color .2s, box-shadow .2s;
  letter-spacing:.04em;
}
.s-num-input:focus { border-color:var(--s-green); box-shadow:0 0 0 3px rgba(16,185,129,.12); }
.s-num-input::placeholder { font-family:'JetBrains Mono',monospace; }

/* gateway status select */
.s-status-select {
  background:var(--s-surface); border:1px solid var(--s-border);
  border-radius:var(--s-radius-sm); padding:9px 14px; color:var(--s-text);
  font-family:inherit; font-size:.82rem; outline:none; cursor:pointer;
  min-width:130px; transition:border-color .2s;
}
.s-status-select:focus { border-color:var(--s-green); }

/* status indicator */
.s-status-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.s-status-active   { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }
.s-status-inactive { background:rgba(239,68,68,.12);  color:#fca5a5; border:1px solid rgba(239,68,68,.25); }

/* info card */
.s-info-box {
  background:rgba(16,185,129,.06); border:1px solid rgba(16,185,129,.15);
  border-radius:var(--s-radius-sm); padding:12px 16px;
  display:flex; align-items:flex-start; gap:10px; margin-bottom:20px;
  font-size:.82rem; color:rgba(52,211,153,.8); line-height:1.5;
}
.s-info-box i { margin-top:2px; flex-shrink:0; }

/* buttons */
.s-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--s-radius-sm); transition:all .2s; text-decoration:none; }
.s-btn-green { background:var(--s-green); color:#022c22; padding:10px 22px; font-size:.875rem; }
.s-btn-green:hover { background:var(--s-green2); transform:translateY(-1px); box-shadow:0 4px 14px rgba(16,185,129,.4); color:#022c22; }
.s-btn-outline { background:transparent; color:var(--s-text); border:1px solid var(--s-border); padding:10px 16px; font-size:.875rem; }
.s-btn-outline:hover { background:var(--s-surface2); color:var(--s-text); }

/* toast */
#s-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.s-toast { display:flex; align-items:center; gap:12px; background:var(--s-surface); border:1px solid var(--s-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.s-toast.show { transform:translateX(0); }
.s-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.s-toast-success .s-toast-icon { background:rgba(34,197,94,.15); color:var(--s-success); }
.s-toast-danger  .s-toast-icon { background:rgba(239,68,68,.15);  color:var(--s-danger); }
.s-toast-title { font-size:.875rem; font-weight:700; color:var(--s-text); }
.s-toast-msg   { font-size:.78rem; color:var(--s-muted); margin-top:2px; }
.s-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:sBar 3.5s linear forwards; }
.s-toast-success .s-toast-bar { background:var(--s-success); }
.s-toast-danger  .s-toast-bar { background:var(--s-danger); }
@keyframes sBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="s-wrap">

  {{-- Hero --}}
  <div class="s-hero">
    <div class="s-hero-nav">
      <div class="s-breadcrumb">
        <a href="{{ route('admin.settings.index') }}"><i class="fas fa-cogs me-1"></i>Settings</a>
        <i class="fas fa-chevron-right mx-2" style="font-size:.55rem;opacity:.5;"></i>
        <span>Payment Settings</span>
      </div>
      <a href="{{ route('admin.settings.index') }}" class="s-btn s-btn-outline" style="font-size:.78rem;padding:7px 14px;">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  {{-- Form Card --}}
  <div class="s-main-card">
    <div class="s-card-header">
      <div class="s-card-icon"><i class="fas fa-credit-card"></i></div>
      <div>
        <div class="s-card-title">Payment Settings</div>
        <div class="s-card-sub">Configure mobile banking numbers and online payment gateways</div>
      </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.payment.update') }}">
      @csrf
      <div class="s-card-body">

        {{-- Mobile Banking --}}
        <div class="s-section"><i class="fas fa-mobile-alt"></i> Mobile Banking Numbers</div>

        <div class="s-info-box">
          <i class="fas fa-info-circle"></i>
          <span>Enter mobile banking numbers where customers can send payments. Leave empty to hide that option.</span>
        </div>

        {{-- bKash --}}
        <div class="s-pay-row">
          <div class="s-pay-logo" style="background:rgba(231,0,116,.1);color:#f43f8e;">
            <i class="fas fa-mobile-alt"></i>
          </div>
          <div class="s-pay-info">
            <div class="s-pay-name" style="color:#f9a8d4;">bKash</div>
            <div class="s-pay-desc">Personal / Merchant number</div>
          </div>
          <div class="s-pay-input-wrap">
            <input type="text" name="bkash_number" class="s-num-input"
              value="{{ old('bkash_number', $settings['bkash_number'] ?? '') }}"
              placeholder="01XXXXXXXXX">
            @error('bkash_number')<div style="color:#fca5a5;font-size:.75rem;margin-top:4px;">{{ $message }}</div>@enderror
          </div>
        </div>

        {{-- Nagad --}}
        <div class="s-pay-row">
          <div class="s-pay-logo" style="background:rgba(249,115,22,.1);color:#fb923c;">
            <i class="fas fa-mobile-alt"></i>
          </div>
          <div class="s-pay-info">
            <div class="s-pay-name" style="color:#fed7aa;">Nagad</div>
            <div class="s-pay-desc">Personal / Merchant number</div>
          </div>
          <div class="s-pay-input-wrap">
            <input type="text" name="nagad_number" class="s-num-input"
              value="{{ old('nagad_number', $settings['nagad_number'] ?? '') }}"
              placeholder="01XXXXXXXXX">
          </div>
        </div>

        {{-- Rocket --}}
        <div class="s-pay-row">
          <div class="s-pay-logo" style="background:rgba(139,92,246,.1);color:#c4b5fd;">
            <i class="fas fa-rocket"></i>
          </div>
          <div class="s-pay-info">
            <div class="s-pay-name" style="color:#c4b5fd;">Rocket</div>
            <div class="s-pay-desc">Dutch-Bangla mobile banking</div>
          </div>
          <div class="s-pay-input-wrap">
            <input type="text" name="rocket_number" class="s-num-input"
              value="{{ old('rocket_number', $settings['rocket_number'] ?? '') }}"
              placeholder="018XXXXXXXX">
          </div>
        </div>

        <div class="s-divider"></div>

        {{-- Online Gateways --}}
        <div class="s-section"><i class="fas fa-globe"></i> Online Payment Gateways</div>

        {{-- Stripe --}}
        <div class="s-pay-row">
          <div class="s-pay-logo" style="background:rgba(99,91,255,.1);color:#818cf8;">
            <i class="fab fa-stripe-s" style="font-size:1.3rem;"></i>
          </div>
          <div class="s-pay-info">
            <div class="s-pay-name" style="color:#a5b4fc;">Stripe</div>
            <div class="s-pay-desc">International card payments</div>
          </div>
          <div>
            @php $stripe = ($settings['stripe_status'] ?? 0) == 1; @endphp
            <span class="s-status-badge {{ $stripe ? 's-status-active' : 's-status-inactive' }}" id="stripe-badge">
              <i class="fas fa-circle" style="font-size:.4rem;"></i>
              {{ $stripe ? 'Active' : 'Inactive' }}
            </span>
          </div>
          <div>
            <select name="stripe_status" class="s-status-select" onchange="updateBadge(this,'stripe-badge')">
              <option value="1" {{ $stripe ? 'selected' : '' }}>✅ Active</option>
              <option value="0" {{ !$stripe ? 'selected' : '' }}>❌ Inactive</option>
            </select>
          </div>
        </div>

        {{-- PayPal --}}
        <div class="s-pay-row">
          <div class="s-pay-logo" style="background:rgba(0,112,240,.1);color:#60a5fa;">
            <i class="fab fa-paypal" style="font-size:1.2rem;"></i>
          </div>
          <div class="s-pay-info">
            <div class="s-pay-name" style="color:#93c5fd;">PayPal</div>
            <div class="s-pay-desc">Global online payments</div>
          </div>
          <div>
            @php $paypal = ($settings['paypal_status'] ?? 0) == 1; @endphp
            <span class="s-status-badge {{ $paypal ? 's-status-active' : 's-status-inactive' }}" id="paypal-badge">
              <i class="fas fa-circle" style="font-size:.4rem;"></i>
              {{ $paypal ? 'Active' : 'Inactive' }}
            </span>
          </div>
          <div>
            <select name="paypal_status" class="s-status-select" onchange="updateBadge(this,'paypal-badge')">
              <option value="1" {{ $paypal ? 'selected' : '' }}>✅ Active</option>
              <option value="0" {{ !$paypal ? 'selected' : '' }}>❌ Inactive</option>
            </select>
          </div>
        </div>

      </div>

      <div class="s-card-footer">
        <a href="{{ route('admin.settings.index') }}" class="s-btn s-btn-outline">Cancel</a>
        <button type="submit" class="s-btn s-btn-green"><i class="fas fa-save"></i> Save Payment Settings</button>
      </div>
    </form>
  </div>

</div>

<div id="s-toast-container"></div>

<script>
// live badge update when select changes
function updateBadge(sel, badgeId) {
  var badge = document.getElementById(badgeId);
  if(sel.value === '1') {
    badge.className = 's-status-badge s-status-active';
    badge.innerHTML = '<i class="fas fa-circle" style="font-size:.4rem;"></i> Active';
  } else {
    badge.className = 's-status-badge s-status-inactive';
    badge.innerHTML = '<i class="fas fa-circle" style="font-size:.4rem;"></i> Inactive';
  }
}

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