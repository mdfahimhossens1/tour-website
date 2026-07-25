@extends('layouts.admin')
@section('title', 'FAQ Management')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --p-surface:   #1a1d27;
  --p-surface2:  #222636;
  --p-border:    rgba(255,255,255,.07);
  --p-accent:    #0ea5e9;
  --p-accent2:   #38bdf8;
  --p-success:   #22c55e;
  --p-danger:    #ef4444;
  --p-warning:   #f59e0b;
  --p-purple:    #8b5cf6;
  --p-teal:      #14b8a6;
  --p-text:      #e2e8f0;
  --p-muted:     #64748b;
  --p-radius:    14px;
  --p-radius-sm: 8px;
  --p-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.faq-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

/* ── HEADER ── */
.faq-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#1a0c2e 50%,#0c1a2e 100%);
  border-radius:var(--p-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.faq-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%238b5cf6' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.faq-header::after {
  content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px;
  border-radius:50%; background:radial-gradient(circle,rgba(139,92,246,.18) 0%,transparent 70%);
}
.faq-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,#c4b5fd);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.faq-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }
.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff;
  position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* ── LAYOUT ── */
.faq-layout { display:grid; grid-template-columns:340px 1fr; gap:20px; align-items:start; }
@media(max-width:900px){ .faq-layout{ grid-template-columns:1fr; } }

/* ── FORM CARD ── */
.faq-form-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden;
  position:sticky; top:20px;
}
.faq-form-card-head {
  padding:18px 22px 16px; border-bottom:1px solid var(--p-border);
  background:var(--p-surface2); display:flex; align-items:center; gap:10px;
}
.faq-form-card-head .icon {
  width:36px; height:36px; border-radius:9px;
  background:rgba(139,92,246,.15); color:#c4b5fd;
  display:flex; align-items:center; justify-content:center; font-size:.85rem;
}
.faq-form-card-head h5 { font-size:.95rem; font-weight:700; margin:0; }
.faq-form-card-head p  { font-size:.75rem; color:var(--p-muted); margin:2px 0 0; }

.faq-form-body { padding:22px; }
.faq-field { margin-bottom:16px; }
.faq-field label {
  display:block; font-size:.75rem; font-weight:700; letter-spacing:.07em;
  text-transform:uppercase; color:var(--p-muted); margin-bottom:7px;
}
.faq-field input, .faq-field select, .faq-field textarea {
  width:100%; background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:var(--p-radius-sm); padding:10px 14px; color:var(--p-text);
  font-family:'Plus Jakarta Sans',sans-serif; font-size:.875rem; outline:none;
  transition:border-color .2s, box-shadow .2s; box-sizing:border-box;
}
.faq-field input:focus, .faq-field select:focus, .faq-field textarea:focus {
  border-color:var(--p-purple); box-shadow:0 0 0 3px rgba(139,92,246,.12);
}
.faq-field textarea { min-height:100px; resize:vertical; }
.faq-field .err { color:#fca5a5; font-size:.76rem; margin-top:5px; display:block; }
.faq-field select option { background:#1a1d27; }

.faq-submit {
  width:100%; padding:11px; border:none; border-radius:var(--p-radius-sm);
  background:linear-gradient(135deg,var(--p-purple),#7c3aed);
  color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
  font-size:.88rem; cursor:pointer; display:flex; align-items:center;
  justify-content:center; gap:8px; transition:all .2s; margin-top:4px;
}
.faq-submit:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(139,92,246,.35); }

/* ── TABLE CARD ── */
.faq-table-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden;
}
.faq-table-head {
  padding:18px 22px 16px; border-bottom:1px solid var(--p-border);
  background:var(--p-surface2); display:flex; align-items:center; justify-content:space-between;
}
.faq-table-head h5 { font-size:.95rem; font-weight:700; margin:0; display:flex; align-items:center; gap:8px; }
.faq-count-badge {
  background:rgba(139,92,246,.15); color:#c4b5fd; border:1px solid rgba(139,92,246,.25);
  border-radius:20px; padding:2px 10px; font-size:.72rem; font-weight:700;
}

.faq-table { width:100%; border-collapse:collapse; }
.faq-table thead tr { background:rgba(255,255,255,.02); border-bottom:1px solid var(--p-border); }
.faq-table th {
  font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
  color:var(--p-muted); padding:12px 18px; text-align:left;
}
.faq-table td {
  padding:14px 18px; border-bottom:1px solid var(--p-border);
  font-size:.875rem; color:var(--p-text); vertical-align:middle;
}
.faq-table tbody tr:last-child td { border-bottom:none; }
.faq-table tbody tr { transition:background .15s; }
.faq-table tbody tr:hover { background:rgba(255,255,255,.02); }

.faq-serial { font-family:'JetBrains Mono',monospace; font-size:.78rem; color:var(--p-muted); }
.faq-question { font-weight:600; font-size:.88rem; max-width:200px; word-break:break-word; }
.faq-answer { font-size:.82rem; color:var(--p-muted); max-width:220px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }
.faq-cat-badge {
  display:inline-flex; align-items:center; gap:5px;
  background:rgba(139,92,246,.12); color:#c4b5fd;
  border:1px solid rgba(139,92,246,.25); border-radius:20px;
  padding:3px 10px; font-size:.72rem; font-weight:600;
}
.faq-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.faq-badge-active   { background:rgba(34,197,94,.12); color:#86efac; border:1px solid rgba(34,197,94,.25); }
.faq-badge-inactive { background:rgba(239,68,68,.12); color:#fca5a5; border:1px solid rgba(239,68,68,.25); }

.faq-actions { display:flex; gap:6px; }
.faq-btn-action {
  display:inline-flex; align-items:center; gap:5px; border-radius:6px;
  padding:5px 11px; font-size:.77rem; font-weight:600; cursor:pointer;
  border:1px solid var(--p-border); font-family:'Plus Jakarta Sans',sans-serif;
  transition:all .2s; text-decoration:none;
}
.faq-btn-edit { background:rgba(245,158,11,.1); color:#fcd34d; border-color:rgba(245,158,11,.2); }
.faq-btn-edit:hover { background:rgba(245,158,11,.2); color:#fcd34d; transform:translateY(-1px); }
.faq-btn-del  { background:rgba(239,68,68,.1); color:#fca5a5; border-color:rgba(239,68,68,.2); }
.faq-btn-del:hover { background:rgba(239,68,68,.2); transform:translateY(-1px); }

.faq-empty { text-align:center; padding:60px 20px; color:var(--p-muted); }
.faq-empty i { font-size:2.2rem; margin-bottom:12px; opacity:.3; display:block; }

/* pagination */
.pagination { padding:14px 18px; }
.pagination .page-item .page-link {
  background:var(--p-surface2); border:1px solid var(--p-border);
  color:var(--p-muted); font-size:.82rem; border-radius:6px !important; margin:0 2px; transition:all .2s;
}
.pagination .page-item .page-link:hover { background:var(--p-border); color:var(--p-text); }
.pagination .page-item.active .page-link { background:var(--p-purple); border-color:var(--p-purple); color:#fff; font-weight:700; }

/* ── MODALS ── */
.faq-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.75); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.faq-overlay.open { opacity:1; pointer-events:auto; }
.faq-modal {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(580px,96vw); max-height:90vh;
  overflow-y:auto; box-shadow:0 24px 64px rgba(0,0,0,.7);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.faq-overlay.open .faq-modal { transform:translateY(0) scale(1); }
.faq-modal::-webkit-scrollbar { width:5px; }
.faq-modal::-webkit-scrollbar-thumb { background:var(--p-border); border-radius:10px; }

.faq-modal-header {
  padding:20px 26px 16px; border-bottom:1px solid var(--p-border);
  display:flex; align-items:center; justify-content:space-between;
  background:var(--p-surface2); position:sticky; top:0; z-index:2;
}
.faq-modal-title { font-size:1.05rem; font-weight:700; display:flex; align-items:center; gap:9px; }
.faq-modal-close {
  background:var(--p-surface); border:1px solid var(--p-border); color:var(--p-muted);
  width:30px; height:30px; border-radius:7px; cursor:pointer;
  display:flex; align-items:center; justify-content:center; transition:all .2s; font-size:.85rem;
}
.faq-modal-close:hover { color:var(--p-text); }
.faq-modal-body   { padding:24px 26px; }
.faq-modal-footer { padding:16px 26px 22px; border-top:1px solid var(--p-border); display:flex; gap:10px; justify-content:flex-end; background:rgba(0,0,0,.1); }

.faq-btn { display:inline-flex; align-items:center; gap:7px; border:none; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif; font-weight:600; border-radius:var(--p-radius-sm); transition:all .2s; font-size:.85rem; padding:9px 20px; }
.faq-btn-outline { background:transparent; color:var(--p-text); border:1px solid var(--p-border); }
.faq-btn-outline:hover { background:var(--p-surface2); }
.faq-btn-warn    { background:rgba(139,92,246,.15); color:#c4b5fd; border:1px solid rgba(139,92,246,.25); }
.faq-btn-warn:hover { background:rgba(139,92,246,.25); transform:translateY(-1px); }
.faq-btn-danger  { background:var(--p-danger); color:#fff; border:none; }
.faq-btn-danger:hover { background:#dc2626; transform:translateY(-1px); }

/* ── DELETE MODAL ── */
.faq-del-modal { max-width:420px; }
.faq-del-body  { text-align:center; padding:34px 28px 16px; }
.faq-del-icon  { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 16px; }
.faq-del-body h5 { font-weight:700; margin-bottom:8px; color:var(--p-text); }
.faq-del-body p  { color:var(--p-muted); font-size:.875rem; line-height:1.6; margin:0; }
.faq-del-footer  { padding:14px 28px 24px; display:flex; gap:10px; justify-content:center; }

/* ── TOAST ── */
#faq-toast { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.faq-toast {
  display:flex; align-items:center; gap:12px; background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:12px; padding:14px 18px; min-width:260px; box-shadow:0 8px 30px rgba(0,0,0,.5);
  transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1);
  font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden;
}
.faq-toast.show { transform:translateX(0); }
.faq-toast-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.faq-toast-s .faq-toast-icon { background:rgba(34,197,94,.15); color:var(--p-success); }
.faq-toast-d .faq-toast-icon { background:rgba(239,68,68,.15); color:var(--p-danger); }
.faq-toast-title { font-size:.875rem; font-weight:700; color:var(--p-text); }
.faq-toast-msg   { font-size:.77rem; color:var(--p-muted); margin-top:1px; }
.faq-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:faqBar 3.2s linear forwards; }
.faq-toast-s .faq-toast-bar { background:var(--p-success); }
.faq-toast-d .faq-toast-bar { background:var(--p-danger); }
@keyframes faqBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-s" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-e"   data-msg="{{ session('error') }}"></div>@endif

<div class="faq-wrap">

  {{-- ── HEADER ── --}}
  <div class="faq-header">
    <div>
      <div class="title"><i class="fas fa-question-circle me-2"></i>FAQ Management</div>
      <div class="subtitle">Create and manage frequently asked questions</div>
      <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
        <span class="stat-pill"><span class="dot" style="background:var(--p-success)"></span>{{ $faqs->where('status',1)->count() }} Active</span>
        <span class="stat-pill"><span class="dot" style="background:var(--p-danger)"></span>{{ $faqs->where('status',0)->count() }} Inactive</span>
        <span class="stat-pill"><span class="dot" style="background:var(--p-purple)"></span>{{ $faqs->total() }} Total</span>
      </div>
    </div>
  </div>

  {{-- ── MAIN LAYOUT ── --}}
  <div class="faq-layout">

    {{-- ── CREATE FORM ── --}}
    <div class="faq-form-card">
      <div class="faq-form-card-head">
        <div class="icon"><i class="fas fa-plus"></i></div>
        <div>
          <h5>New FAQ</h5>
          <p>Add a new frequently asked question</p>
        </div>
      </div>
      <div class="faq-form-body">
        <form method="POST" action="{{ route('admin.faqs.store') }}">
          @csrf
          <div class="faq-field">
            <label>Category <span style="color:var(--p-danger)">*</span></label>
            <select name="faq_category_id" id="faq_category_id">
              <option value="">Select Category</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('faq_category_id')==$category->id?'selected':'' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('faq_category_id')<span class="err">{{ $message }}</span>@enderror
          </div>
          <div class="faq-field">
            <label>Question <span style="color:var(--p-danger)">*</span></label>
            <input type="text" name="question" placeholder="e.g. What is your return policy?" value="{{ old('question') }}">
            @error('question')<span class="err">{{ $message }}</span>@enderror
          </div>
          <div class="faq-field">
            <label>Answer <span style="color:var(--p-danger)">*</span></label>
            <textarea name="answer" placeholder="Write the answer here...">{{ old('answer') }}</textarea>
            @error('answer')<span class="err">{{ $message }}</span>@enderror
          </div>
          <div class="faq-field">
            <label>Status</label>
            <select name="status">
              <option value="1" {{ old('status','1')=='1'?'selected':'' }}>Active</option>
              <option value="0" {{ old('status')=='0'?'selected':'' }}>Inactive</option>
            </select>
          </div>
          <button type="submit" class="faq-submit">
            <i class="fas fa-save"></i> Save FAQ
          </button>
        </form>
      </div>
    </div>

    {{-- ── TABLE ── --}}
    <div class="faq-table-card">
      <div class="faq-table-head">
        <h5>
          <i class="fas fa-list" style="color:var(--p-purple);"></i>
          All FAQs
          <span class="faq-count-badge">{{ $faqs->total() }}</span>
        </h5>
      </div>

      <div class="table-responsive">
        <table class="faq-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Category</th>
              <th>Question</th>
              <th>Answer</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($faqs as $key => $faq)
            <tr>
              <td><span class="faq-serial">{{ str_pad($faqs->firstItem() + $key, 2, '0', STR_PAD_LEFT) }}</span></td>
              <td>
                <span class="faq-cat-badge">
                  <i class="fas fa-folder" style="font-size:.55rem;"></i>
                  {{ $faq->category->name ?? 'Uncategorized' }}
                </span>
              </td>
              <td><span class="faq-question">{{ Str::limit($faq->question, 60) }}</span></td>
              <td><span class="faq-answer">{{ Str::limit($faq->answer, 80) }}</span></td>
              <td>
                @if($faq->status)
                  <span class="faq-badge faq-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Active</span>
                @else
                  <span class="faq-badge faq-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Inactive</span>
                @endif
              </td>
              <td>
                <div class="faq-actions">
<button
class="faq-btn-action faq-btn-edit editBtn"

data-id="{{ $faq->id }}"
data-category="{{ $faq->faq_category_id }}"
data-question='@json($faq->question)'
data-answer='@json($faq->answer)'
data-status="{{ $faq->status }}"
>
Edit
</button>
<button
    class="faq-btn-action faq-btn-del deleteBtn"
    data-id="{{ $faq->id }}"
    data-question="{{ e($faq->question) }}"
>
    <i class="fas fa-trash-alt"></i>
</button>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6">
                <div class="faq-empty">
                  <i class="fas fa-question-circle"></i>
                  <p>No FAQs yet. Create your first one!</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($faqs->hasPages())
        <div class="pagination">{{ $faqs->links() }}</div>
        <div style="height:14px;"></div>
      @endif
    </div>

  </div>
</div>

{{-- ══════════════════════════════
     EDIT MODAL
══════════════════════════════ --}}
<div class="faq-overlay" id="editModal">
  <div class="faq-modal">
    <div class="faq-modal-header">
      <div class="faq-modal-title">
        <i class="fas fa-pen" style="color:var(--p-warning);"></i> Edit FAQ
      </div>
      <button class="faq-modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editForm">
      @csrf
      <div class="faq-modal-body">
        <div class="faq-field">
          <label>Category <span style="color:var(--p-danger)">*</span></label>
          <select name="faq_category_id" id="edit_category">
            <option value="">Select Category</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="faq-field">
          <label>Question <span style="color:var(--p-danger)">*</span></label>
          <input type="text" name="question" id="edit_question" placeholder="Enter question">
        </div>
        <div class="faq-field">
          <label>Answer <span style="color:var(--p-danger)">*</span></label>
          <textarea name="answer" id="edit_answer" placeholder="Enter answer" rows="4"></textarea>
        </div>
        <div class="faq-field">
          <label>Status</label>
          <select name="status" id="edit_status">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
      </div>
      <div class="faq-modal-footer">
        <button type="button" class="faq-btn faq-btn-outline" onclick="closeModal('editModal')">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button type="submit" class="faq-btn faq-btn-warn">
          <i class="fas fa-save"></i> Update FAQ
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ══════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════ --}}
<div class="faq-overlay" id="deleteModal">
  <div class="faq-modal faq-del-modal">
    <div class="faq-del-body">
      <div class="faq-del-icon"><i class="fas fa-question-circle"></i></div>
      <h5>Delete FAQ?</h5>
      <p>
        <strong id="delete-question" style="color:var(--p-purple);display:block;margin-bottom:6px;"></strong>
        This FAQ will be permanently deleted.
        <br>This action <strong style="color:var(--p-danger)">cannot be undone</strong>.
      </p>
    </div>
    <div class="faq-del-footer">
      <button class="faq-btn faq-btn-outline" onclick="closeModal('deleteModal')">
        <i class="fas fa-times"></i> Cancel
      </button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="faq-btn faq-btn-danger">
          <i class="fas fa-trash-alt"></i> Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<div id="faq-toast"></div>

<script>
"use strict";

/* ============================
    Modal
============================ */

function openModal(id) {
    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.add("open");
    document.body.style.overflow = "hidden";
}

function closeModal(id) {

    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.remove("open");
    document.body.style.overflow = "";
}


/* ============================
    Overlay Close
============================ */

document.querySelectorAll(".faq-overlay").forEach(function (overlay) {

    overlay.addEventListener("click", function (e) {

        if (e.target === overlay) {

            closeModal(overlay.id);

        }

    });

});


/* ============================
    ESC Close
============================ */

document.addEventListener("keydown", function (e) {

    if (e.key === "Escape") {

        closeModal("editModal");
        closeModal("deleteModal");

    }

});


/* ============================
    Edit Button
============================ */

document.querySelectorAll(".editBtn").forEach(function(btn){

    btn.addEventListener("click", function(){

        document.getElementById("edit_category").value =
            this.dataset.category;

        document.getElementById("edit_question").value =
            this.dataset.question;

        document.getElementById("edit_answer").value =
            this.dataset.answer;

        document.getElementById("edit_status").value =
            this.dataset.status;

        document.getElementById("editForm").action =
            "{{ route('admin.faqs.update', ':id') }}"
            .replace(":id", this.dataset.id);

        openModal("editModal");

    });

});


/* ============================
    Delete Button
============================ */

document.querySelectorAll(".deleteBtn").forEach(function(btn){

    btn.addEventListener("click", function(){

        document.getElementById("delete-question").textContent =
            this.dataset.question;

        document.getElementById("deleteForm").action =
            "{{ route('admin.faqs.delete', ':id') }}"
            .replace(":id", this.dataset.id);

        openModal("deleteModal");

    });

});


/* ============================
    Toast
============================ */

function showToast(type, title, message){

    const container = document.getElementById("faq-toast");

    if(!container) return;

    const toast = document.createElement("div");

    toast.className = "faq-toast faq-toast-" + type;

    const icon =
        type === "s"
            ? "fas fa-check-circle"
            : "fas fa-exclamation-circle";

    toast.innerHTML = `
        <div class="faq-toast-icon">
            <i class="${icon}"></i>
        </div>

        <div>
            <div class="faq-toast-title">${title}</div>
            <div class="faq-toast-msg">${message}</div>
        </div>

        <span class="faq-toast-bar"></span>
    `;

    container.appendChild(toast);

    setTimeout(function(){

        toast.classList.add("show");

    },20);

    setTimeout(function(){

        toast.classList.remove("show");

        setTimeout(function(){

            toast.remove();

        },400);

    },3500);

}


/* ============================
    Flash Message
============================ */

document.addEventListener("DOMContentLoaded", function(){

    const success =
        document.getElementById("flash-s");

    const error =
        document.getElementById("flash-e");

    if(success){

        showToast(
            "s",
            "Success",
            success.dataset.msg
        );

    }

    if(error){

        showToast(
            "d",
            "Error",
            error.dataset.msg
        );

    }

});
</script>

@endsection