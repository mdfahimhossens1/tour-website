@extends('layouts.admin')

@section('title', 'Create Blog')

@section('page')

<style>
/* =========================================================
   BLOG CREATE
   ========================================================= */

@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

:root {
    --p-surface: #1a1d27;
    --p-surface2: #222636;
    --p-border: rgba(255,255,255,.07);
    --p-accent: #0ea5e9;
    --p-accent2: #38bdf8;
    --p-success: #22c55e;
    --p-danger: #ef4444;
    --p-warning: #f59e0b;
    --p-teal: #14b8a6;
    --p-text: #e2e8f0;
    --p-muted: #64748b;
    --p-radius: 14px;
    --p-radius-sm: 8px;
    --p-shadow: 0 8px 32px rgba(0,0,0,.45);
}

.bc-wrap {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--p-text);
}

.bc-header {
    background: linear-gradient(135deg,#0c1a2e 0%,#0c2218 60%,#0c1a2e 100%);
    border-radius: var(--p-radius);
    padding: 26px 32px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--p-shadow);

    display: flex;
    align-items: center;
    justify-content: space-between;
}

.bc-header::after {
    content: '';
    position: absolute;
    right: -40px;
    top: -40px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: radial-gradient(
        circle,
        rgba(20,184,166,.15) 0%,
        transparent 70%
    );
}

.bc-header .title {
    font-size: 1.35rem;
    font-weight: 700;
    position: relative;
    z-index: 1;

    background: linear-gradient(90deg,#fff,#5eead4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.bc-header .subtitle {
    color: rgba(255,255,255,.45);
    font-size: .82rem;
    margin-top: 3px;
    position: relative;
    z-index: 1;
}

.bc-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    position: relative;
    z-index: 1;

    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    color: #fff;

    border-radius: 9px;
    padding: 8px 16px;

    font-size: .83rem;
    font-weight: 600;
    text-decoration: none;

    transition: background .2s, transform .2s;
}

.bc-back:hover {
    background: rgba(255,255,255,.14);
    color: #fff;
    transform: translateY(-1px);
}

.bc-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 20px;
    align-items: start;
}

@media(max-width:960px) {
    .bc-layout {
        grid-template-columns: 1fr;
    }
}

.bc-card {
    background: var(--p-surface);
    border: 1px solid var(--p-border);
    border-radius: var(--p-radius);
    box-shadow: var(--p-shadow);
    overflow: hidden;
}

.bc-card-head {
    padding: 15px 22px;
    border-bottom: 1px solid var(--p-border);
    background: var(--p-surface2);

    display: flex;
    align-items: center;
    gap: 10px;
}

.bc-card-head .ic {
    width: 34px;
    height: 34px;
    border-radius: 8px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: .82rem;
}

.bc-card-head h5 {
    font-size: .9rem;
    font-weight: 700;
    margin: 0;
}

.bc-card-head p {
    font-size: .73rem;
    color: var(--p-muted);
    margin: 2px 0 0;
}

.bc-body {
    padding: 22px;
}

.bc-field {
    margin-bottom: 17px;
}

.bc-field > label {
    display: block;
    font-size: .73rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--p-muted);
    margin-bottom: 7px;
}

.bc-field label .req {
    color: var(--p-danger);
}

.bc-field input,
.bc-field select {
    width: 100%;
    background: var(--p-surface2);
    border: 1px solid var(--p-border);
    border-radius: var(--p-radius-sm);

    padding: 10px 14px;
    color: var(--p-text);

    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .875rem;

    outline: none;
    box-sizing: border-box;

    transition: border-color .2s, box-shadow .2s;
}

.bc-field input:focus,
.bc-field select:focus {
    border-color: var(--p-teal);
    box-shadow: 0 0 0 3px rgba(20,184,166,.12);
}

.bc-field select option {
    background: #1a1d27;
}

/* =========================================================
   RICH TEXT EDITOR
   ========================================================= */

.blog-editor {
    border: 1px solid var(--p-border);
    border-radius: 11px;
    overflow: hidden;
    background: #fff;
}

/* Toolbar */

.blog-toolbar {
    background: #f3f4f6;
    border-bottom: 1px solid #d1d5db;

    padding: 8px;

    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
}

.blog-toolbar-group {
    display: flex;
    align-items: center;
    gap: 3px;

    padding-right: 7px;
    margin-right: 4px;

    border-right: 1px solid #d1d5db;
}

.blog-toolbar-group:last-child {
    border-right: none;
}

/* Toolbar buttons */

.blog-tool {
    min-width: 34px;
    height: 32px;

    padding: 0 8px;

    border: 1px solid transparent;
    border-radius: 6px;

    background: transparent;
    color: #374151;

    cursor: pointer;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;

    font-family: Arial, sans-serif;
    font-size: 13px;
    font-weight: 600;

    transition:
        background .15s,
        border-color .15s,
        color .15s;
}

.blog-tool:hover {
    background: #e5e7eb;
    border-color: #d1d5db;
}

.blog-tool.active {
    background: #d1fae5;
    color: #047857;
    border-color: #a7f3d0;
}

.blog-tool i {
    font-size: 13px;
}

/* Selects */

.blog-select {
    height: 32px;

    border: 1px solid #d1d5db;
    border-radius: 6px;

    background: #fff;
    color: #374151;

    padding: 0 8px;

    font-family: Arial, sans-serif;
    font-size: 12px;

    outline: none;
    cursor: pointer;
}

.blog-select:focus {
    border-color: #14b8a6;
}

/* Color controls */

.blog-color-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
}

.blog-color-label {
    width: 34px;
    height: 32px;

    border: 1px solid transparent;
    border-radius: 6px;

    display: flex;
    align-items: center;
    justify-content: center;

    cursor: pointer;
    color: #374151;

    position: relative;
}

.blog-color-label:hover {
    background: #e5e7eb;
}

.blog-color-label input {
    position: absolute;
    opacity: 0;
    width: 1px;
    height: 1px;
}

.blog-color-line {
    position: absolute;
    bottom: 4px;
    left: 8px;
    right: 8px;
    height: 3px;
    border-radius: 3px;
    background: #111827;
}

/* Editor area */

.blog-editor-area {
    min-height: 480px;

    padding: 28px 32px;

    color: #111827;
    background: #fff;

    outline: none;

    font-family: 'Times New Roman',
                 'Noto Sans Bengali',
                 serif;

    font-size: 18px;
    line-height: 1.8;

    word-break: break-word;
}

/* Placeholder */

.blog-editor-area:empty::before {
    content: attr(data-placeholder);
    color: #9ca3af;
    pointer-events: none;
}

/* Paragraph */

.blog-editor-area p {
    margin: 0 0 18px;
}

/* Headings */

.blog-editor-area h1 {
    font-size: 32px;
    line-height: 1.3;
    margin: 22px 0 14px;
    font-weight: 700;
}

.blog-editor-area h2 {
    font-size: 27px;
    line-height: 1.35;
    margin: 20px 0 12px;
    font-weight: 700;
}

.blog-editor-area h3 {
    font-size: 23px;
    line-height: 1.4;
    margin: 18px 0 10px;
    font-weight: 700;
}

.blog-editor-area h4 {
    font-size: 20px;
    line-height: 1.45;
    margin: 16px 0 9px;
    font-weight: 700;
}

/* Lists */

.blog-editor-area ul,
.blog-editor-area ol {
    margin: 12px 0 20px;
    padding-left: 34px;
}

.blog-editor-area li {
    margin-bottom: 7px;
}

/* Links */

.blog-editor-area a {
    color: #0ea5e9;
    text-decoration: underline;
}

/* Quote */

.blog-editor-area blockquote {
    border-left: 4px solid #14b8a6;
    margin: 20px 0;
    padding: 12px 18px;

    background: #f0fdfa;
    color: #374151;

    font-style: italic;
}

/* Image */

.blog-editor-area img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

/* Horizontal rule */

.blog-editor-area hr {
    border: 0;
    border-top: 1px solid #d1d5db;
    margin: 25px 0;
}

/* Editor helper */

.blog-editor-info {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 7px 12px;

    background: #f8fafc;
    border-top: 1px solid #e5e7eb;

    color: #6b7280;
    font-size: 11px;

    font-family: Arial, sans-serif;
}

.blog-editor-info strong {
    color: #374151;
}

/* =========================================================
   UPLOAD
   ========================================================= */

.bc-upload {
    border: 2px dashed var(--p-border);
    border-radius: 10px;

    padding: 22px;
    text-align: center;

    cursor: pointer;

    transition: all .2s;

    position: relative;
    background: var(--p-surface2);
}

.bc-upload:hover {
    border-color: var(--p-teal);
    background: rgba(20,184,166,.05);
}

.bc-upload input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;

    width: 100%;
    height: 100%;
}

.bc-upload i {
    font-size: 1.4rem;
    color: var(--p-muted);

    display: block;
    margin-bottom: 6px;
}

.bc-upload span {
    font-size: .8rem;
    color: var(--p-muted);
}

.bc-preview {
    display: none;

    width: 100%;
    height: 160px;

    object-fit: cover;

    border-radius: 9px;
    border: 1px solid var(--p-border);

    margin-top: 12px;
}

/* =========================================================
   STATUS
   ========================================================= */

.bc-status-row {
    display: flex;
    gap: 10px;
}

.bc-status-opt {
    flex: 1;
}

.bc-status-opt input[type="radio"] {
    display: none;
}

.bc-status-opt label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;

    padding: 10px;

    border-radius: 8px;
    border: 1px solid var(--p-border);

    cursor: pointer;

    font-size: .83rem;
    font-weight: 600;

    transition: all .2s;

    background: var(--p-surface2);
    color: var(--p-muted);

    text-transform: none;
    letter-spacing: 0;
}

.bc-status-opt input:checked + label.lbl-pub {
    background: rgba(34,197,94,.12);
    color: #86efac;
    border-color: rgba(34,197,94,.3);
}

.bc-status-opt input:checked + label.lbl-dft {
    background: rgba(239,68,68,.12);
    color: #fca5a5;
    border-color: rgba(239,68,68,.3);
}

/* =========================================================
   SUBMIT
   ========================================================= */

.bc-submit {
    width: 100%;

    padding: 12px;

    border: none;
    border-radius: var(--p-radius-sm);

    background: linear-gradient(
        135deg,
        var(--p-teal),
        #0d9488
    );

    color: #fff;

    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: .9rem;

    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    transition: all .2s;

    margin-top: 4px;
}

.bc-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(20,184,166,.35);
}

/* =========================================================
   ERROR
   ========================================================= */

.bc-err-box {
    background: rgba(239,68,68,.08);
    border: 1px solid rgba(239,68,68,.2);

    border-radius: 10px;

    padding: 14px 18px;

    margin-bottom: 18px;
}

.bc-err-box ul {
    margin: 0;
    padding-left: 18px;
}

.bc-err-box li {
    font-size: .83rem;
    color: #fca5a5;
    margin-bottom: 3px;
}

/* =========================================================
   MOBILE EDITOR
   ========================================================= */

@media(max-width:600px) {

    .bc-header {
        padding: 20px;
        gap: 15px;
    }

    .bc-header .title {
        font-size: 1.1rem;
    }

    .bc-body {
        padding: 15px;
    }

    .blog-editor-area {
        padding: 20px;
        min-height: 400px;
        font-size: 16px;
    }

    .blog-toolbar {
        padding: 6px;
    }

    .blog-tool {
        min-width: 30px;
        height: 30px;
        padding: 0 6px;
    }

    .blog-select {
        max-width: 110px;
    }
}
</style>


<div class="bc-wrap">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="bc-header">

        <div>
            <div class="title">
                <i class="fas fa-pen-nib me-2"></i>
                Create Blog Post
            </div>

            <div class="subtitle">
                Write and publish a new blog article
            </div>
        </div>

        <a href="{{ route('admin.blogs.index') }}" class="bc-back">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

    </div>


    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if($errors->any())

        <div class="bc-err-box">

            <ul>

                @foreach($errors->all() as $e)

                    <li>{{ $e }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         FORM
    ====================================================== --}}

    <form
        action="{{ route('admin.blogs.store') }}"
        method="POST"
        enctype="multipart/form-data"
        id="blogCreateForm"
    >

        @csrf


        <div class="bc-layout">


            {{-- =================================================
                 LEFT SIDE
            ================================================== --}}

            <div style="display:flex;flex-direction:column;gap:18px;">


                {{-- POST CONTENT --}}

                <div class="bc-card">

                    <div class="bc-card-head">

                        <div
                            class="ic"
                            style="background:rgba(20,184,166,.15);color:#5eead4;"
                        >
                            <i class="fas fa-heading"></i>
                        </div>

                        <div>

                            <h5>
                                Post Content
                            </h5>

                            <p>
                                Title, category and main content
                            </p>

                        </div>

                    </div>


                    <div class="bc-body">


                        {{-- TITLE --}}

                        <div class="bc-field">

                            <label>
                                Blog Title
                                <span class="req">*</span>
                            </label>

                            <input
                                type="text"
                                name="title"
                                value="{{ old('title') }}"
                                placeholder="Enter an engaging blog title..."
                            >

                            @error('title')

                                <span
                                    style="
                                        color:#fca5a5;
                                        font-size:.76rem;
                                        margin-top:5px;
                                        display:block;
                                    "
                                >
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        {{-- CATEGORY --}}

                        <div class="bc-field">

                            <label>
                                Category
                                <span class="req">*</span>
                            </label>

                            <select name="blog_category_id">

                                <option value="">
                                    — Select Category —
                                </option>

                                @foreach($categories as $cat)

                                    <option
                                        value="{{ $cat->id }}"
                                        {{ old('blog_category_id') == $cat->id ? 'selected' : '' }}
                                    >
                                        {{ $cat->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('blog_category_id')

                                <span
                                    style="
                                        color:#fca5a5;
                                        font-size:.76rem;
                                        margin-top:5px;
                                        display:block;
                                    "
                                >
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>


                        {{-- =================================================
                             DESCRIPTION / WORD EDITOR
                        ================================================== --}}

                        <div class="bc-field" style="margin-bottom:0;">

                            <label>
                                Description
                                <span class="req">*</span>
                            </label>


                            <div class="blog-editor">


                                {{-- TOOLBAR --}}

                                <div class="blog-toolbar">


                                    {{-- UNDO REDO --}}

                                    <div class="blog-toolbar-group">

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('undo')"
                                            title="Undo"
                                        >
                                            <i class="fas fa-undo"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('redo')"
                                            title="Redo"
                                        >
                                            <i class="fas fa-redo"></i>
                                        </button>

                                    </div>


                                    {{-- FORMAT --}}

                                    <div class="blog-toolbar-group">

                                        <select
                                            class="blog-select"
                                            onchange="formatBlock(this.value); this.selectedIndex=0;"
                                        >
                                            <option value="">
                                                Format
                                            </option>

                                            <option value="p">
                                                Paragraph
                                            </option>

                                            <option value="h1">
                                                Heading 1
                                            </option>

                                            <option value="h2">
                                                Heading 2
                                            </option>

                                            <option value="h3">
                                                Heading 3
                                            </option>

                                            <option value="h4">
                                                Heading 4
                                            </option>

                                            <option value="blockquote">
                                                Quote
                                            </option>

                                        </select>


                                        <select
                                            class="blog-select"
                                            onchange="changeFontSize(this.value); this.selectedIndex=0;"
                                        >
                                            <option value="">
                                                Size
                                            </option>

                                            <option value="1">
                                                Small
                                            </option>

                                            <option value="3">
                                                Normal
                                            </option>

                                            <option value="5">
                                                Large
                                            </option>

                                            <option value="7">
                                                Huge
                                            </option>

                                        </select>

                                    </div>


                                    {{-- FONT --}}

                                    <div class="blog-toolbar-group">

                                        <select
                                            class="blog-select"
                                            onchange="changeFont(this.value); this.selectedIndex=0;"
                                        >

                                            <option value="">
                                                Font
                                            </option>

                                            <option value="Times New Roman">
                                                Times New Roman
                                            </option>

                                            <option value="Noto Sans Bengali">
                                                বাংলা Font
                                            </option>

                                            <option value="Arial">
                                                Arial
                                            </option>

                                            <option value="Georgia">
                                                Georgia
                                            </option>

                                            <option value="Courier New">
                                                Courier
                                            </option>

                                        </select>

                                    </div>


                                    {{-- BASIC FORMATTING --}}

                                    <div class="blog-toolbar-group">

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('bold')"
                                            title="Bold"
                                        >
                                            <strong>B</strong>
                                        </button>

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('italic')"
                                            title="Italic"
                                        >
                                            <em>I</em>
                                        </button>

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('underline')"
                                            title="Underline"
                                        >
                                            <u>U</u>
                                        </button>

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('strikeThrough')"
                                            title="Strike"
                                        >
                                            <s>S</s>
                                        </button>

                                    </div>


                                    {{-- TEXT COLOR --}}

                                    <div class="blog-toolbar-group">

                                        <div class="blog-color-wrap">

                                            <label
                                                class="blog-color-label"
                                                title="Text Color"
                                            >

                                                <i class="fas fa-font"></i>

                                                <span
                                                    class="blog-color-line"
                                                    id="textColorLine"
                                                ></span>

                                                <input
                                                    type="color"
                                                    id="textColorPicker"
                                                    value="#111827"
                                                    onchange="changeTextColor(this.value)"
                                                >

                                            </label>

                                        </div>


                                        <div class="blog-color-wrap">

                                            <label
                                                class="blog-color-label"
                                                title="Highlight"
                                            >

                                                <i class="fas fa-highlighter"></i>

                                                <span
                                                    class="blog-color-line"
                                                    id="highlightColorLine"
                                                    style="background:#fef08a;"
                                                ></span>

                                                <input
                                                    type="color"
                                                    id="highlightColorPicker"
                                                    value="#fef08a"
                                                    onchange="changeHighlight(this.value)"
                                                >

                                            </label>

                                        </div>

                                    </div>


                                    {{-- ALIGNMENT --}}

                                    <div class="blog-toolbar-group">

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('justifyLeft')"
                                            title="Align Left"
                                        >
                                            <i class="fas fa-align-left"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('justifyCenter')"
                                            title="Center"
                                        >
                                            <i class="fas fa-align-center"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('justifyRight')"
                                            title="Align Right"
                                        >
                                            <i class="fas fa-align-right"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('justifyFull')"
                                            title="Justify"
                                        >
                                            <i class="fas fa-align-justify"></i>
                                        </button>

                                    </div>


                                    {{-- LIST --}}

                                    <div class="blog-toolbar-group">

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('insertUnorderedList')"
                                            title="Bullet List"
                                        >
                                            <i class="fas fa-list-ul"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('insertOrderedList')"
                                            title="Number List"
                                        >
                                            <i class="fas fa-list-ol"></i>
                                        </button>

                                    </div>


                                    {{-- LINK --}}

                                    <div class="blog-toolbar-group">

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="insertLink()"
                                            title="Insert Link"
                                        >
                                            <i class="fas fa-link"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="editorCommand('unlink')"
                                            title="Remove Link"
                                        >
                                            <i class="fas fa-unlink"></i>
                                        </button>

                                    </div>


                                    {{-- SPECIAL --}}

                                    <div class="blog-toolbar-group">

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="insertHorizontalLine()"
                                            title="Horizontal Line"
                                        >
                                            <i class="fas fa-minus"></i>
                                        </button>

                                        <button
                                            type="button"
                                            class="blog-tool"
                                            onclick="clearFormatting()"
                                            title="Clear Formatting"
                                        >
                                            <i class="fas fa-remove-format"></i>
                                        </button>

                                    </div>

                                </div>


                                {{-- ACTUAL EDITOR --}}

                                <div
                                    id="blogEditor"
                                    class="blog-editor-area"
                                    contenteditable="true"
                                    data-placeholder="Write your blog content here..."
                                >{!! old('description') !!}</div>


                                {{-- HIDDEN DESCRIPTION --}}

                                <input
                                    type="hidden"
                                    name="description"
                                    id="descriptionInput"
                                    value=""
                                >


                                {{-- EDITOR FOOTER --}}

                                <div class="blog-editor-info">

                                    <span>
                                        <strong>Rich Text Editor</strong>
                                        — Format your article like MS Word
                                    </span>

                                    <span id="wordCounter">
                                        0 words
                                    </span>

                                </div>

                            </div>


                            @error('description')

                                <span
                                    style="
                                        color:#fca5a5;
                                        font-size:.76rem;
                                        margin-top:6px;
                                        display:block;
                                    "
                                >
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     SEO
                ================================================== --}}

                <div class="bc-card">

                    <div class="bc-card-head">

                        <div
                            class="ic"
                            style="background:rgba(14,165,233,.15);color:var(--p-accent2);"
                        >
                            <i class="fas fa-search"></i>
                        </div>

                        <div>

                            <h5>
                                SEO Meta
                            </h5>

                            <p>
                                Optional — for search engines
                            </p>

                        </div>

                    </div>


                    <div class="bc-body">

                        <div class="bc-field">

                            <label>
                                Meta Title
                            </label>

                            <input
                                type="text"
                                name="meta_title"
                                value="{{ old('meta_title') }}"
                                placeholder="SEO title for search results"
                            >

                        </div>


                        <div class="bc-field" style="margin-bottom:0;">

                            <label>
                                Meta Description
                            </label>

                            <textarea
                                name="meta_description"
                                rows="3"
                                style="
                                    width:100%;
                                    background:var(--p-surface2);
                                    border:1px solid var(--p-border);
                                    border-radius:var(--p-radius-sm);
                                    padding:10px 14px;
                                    color:var(--p-text);
                                    font-family:'Plus Jakarta Sans',sans-serif;
                                    font-size:.875rem;
                                    outline:none;
                                    box-sizing:border-box;
                                    resize:vertical;
                                "
                                placeholder="Brief description shown in search results..."
                            >{{ old('meta_description') }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RIGHT SIDE
            ================================================== --}}

            <div
                style="
                    display:flex;
                    flex-direction:column;
                    gap:18px;
                    position:sticky;
                    top:20px;
                "
            >


                {{-- PUBLISH --}}

                <div class="bc-card">

                    <div class="bc-card-head">

                        <div
                            class="ic"
                            style="background:rgba(34,197,94,.15);color:#86efac;"
                        >
                            <i class="fas fa-paper-plane"></i>
                        </div>

                        <div>

                            <h5>
                                Publish
                            </h5>

                            <p>
                                Set visibility and save
                            </p>

                        </div>

                    </div>


                    <div class="bc-body">

                        <div
                            class="bc-field"
                            style="margin-bottom:14px;"
                        >

                            <label>
                                Visibility
                            </label>

                            <div class="bc-status-row">


                                <div class="bc-status-opt">

                                    <input
                                        type="radio"
                                        name="status"
                                        id="s1"
                                        value="1"
                                        {{ old('status','1') == '1' ? 'checked' : '' }}
                                    >

                                    <label
                                        for="s1"
                                        class="lbl-pub"
                                    >
                                        <i class="fas fa-globe"></i>
                                        Published
                                    </label>

                                </div>


                                <div class="bc-status-opt">

                                    <input
                                        type="radio"
                                        name="status"
                                        id="s0"
                                        value="0"
                                        {{ old('status') == '0' ? 'checked' : '' }}
                                    >

                                    <label
                                        for="s0"
                                        class="lbl-dft"
                                    >
                                        <i class="fas fa-lock"></i>
                                        Draft
                                    </label>

                                </div>

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="bc-submit"
                            id="submitBlogBtn"
                        >
                            <i class="fas fa-save"></i>
                            Publish Post
                        </button>

                    </div>

                </div>


                {{-- COVER IMAGE --}}

                <div class="bc-card">

                    <div class="bc-card-head">

                        <div
                            class="ic"
                            style="background:rgba(139,92,246,.15);color:#c4b5fd;"
                        >
                            <i class="fas fa-image"></i>
                        </div>

                        <div>

                            <h5>
                                Cover Image
                            </h5>

                            <p>
                                Featured image for the post
                            </p>

                        </div>

                    </div>


                    <div class="bc-body">

                        <div
                            class="bc-upload"
                            id="uploadZone"
                        >

                            <input
                                type="file"
                                name="image"
                                id="imgInput"
                                accept="image/*"
                                onchange="previewImg(this)"
                            >

                            <i class="fas fa-cloud-upload-alt"></i>

                            <span id="uploadLabel">
                                Click to upload cover image
                            </span>

                        </div>


                        <img
                            id="imgPreview"
                            class="bc-preview"
                            src=""
                            alt=""
                        >


                        @error('image')

                            <span
                                style="
                                    color:#fca5a5;
                                    font-size:.76rem;
                                    margin-top:6px;
                                    display:block;
                                "
                            >
                                {{ $message }}
                            </span>

                        @enderror

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<script>
/* =========================================================
   BLOG RICH TEXT EDITOR
   ========================================================= */

(function () {

    const editor = document.getElementById('blogEditor');
    const hiddenInput = document.getElementById('descriptionInput');
    const form = document.getElementById('blogCreateForm');
    const counter = document.getElementById('wordCounter');

    if (!editor || !hiddenInput || !form) {
        return;
    }


    /* -------------------------------------------------------
       Focus editor
    ------------------------------------------------------- */

    function focusEditor() {

        editor.focus();

    }


    /* -------------------------------------------------------
       Save Selection
    ------------------------------------------------------- */

    let savedRange = null;

    function saveSelection() {

        const selection = window.getSelection();

        if (!selection || selection.rangeCount === 0) {
            return;
        }

        const range = selection.getRangeAt(0);

        if (editor.contains(range.commonAncestorContainer)) {

            savedRange = range.cloneRange();

        }

    }


    function restoreSelection() {

        if (!savedRange) {

            focusEditor();

            return;

        }

        const selection = window.getSelection();

        selection.removeAllRanges();

        selection.addRange(savedRange);

    }


    editor.addEventListener('keyup', saveSelection);
    editor.addEventListener('mouseup', saveSelection);
    editor.addEventListener('input', saveSelection);


    /* -------------------------------------------------------
       Command
    ------------------------------------------------------- */

    window.editorCommand = function (command, value = null) {

        restoreSelection();

        document.execCommand(
            command,
            false,
            value
        );

        saveSelection();

        updateCounter();

        editor.focus();

    };


    /* -------------------------------------------------------
       Format Block
    ------------------------------------------------------- */

    window.formatBlock = function (tag) {

        if (!tag) {
            return;
        }

        restoreSelection();

        document.execCommand(
            'formatBlock',
            false,
            tag
        );

        saveSelection();

        updateCounter();

        editor.focus();

    };


    /* -------------------------------------------------------
       Font Size
    ------------------------------------------------------- */

    window.changeFontSize = function (size) {

        if (!size) {
            return;
        }

        restoreSelection();

        document.execCommand(
            'fontSize',
            false,
            size
        );

        saveSelection();

        editor.focus();

    };


    /* -------------------------------------------------------
       Font Family
    ------------------------------------------------------- */

    window.changeFont = function (font) {

        if (!font) {
            return;
        }

        restoreSelection();

        document.execCommand(
            'fontName',
            false,
            font
        );

        saveSelection();

        editor.focus();

    };


    /* -------------------------------------------------------
       Text Color
    ------------------------------------------------------- */

    window.changeTextColor = function (color) {

        restoreSelection();

        document.execCommand(
            'foreColor',
            false,
            color
        );

        document.getElementById(
            'textColorLine'
        ).style.background = color;

        saveSelection();

        editor.focus();

    };


    /* -------------------------------------------------------
       Highlight
    ------------------------------------------------------- */

    window.changeHighlight = function (color) {

        restoreSelection();

        let success = false;

        try {

            success = document.execCommand(
                'hiliteColor',
                false,
                color
            );

        } catch (e) {

            success = false;

        }


        if (!success) {

            try {

                document.execCommand(
                    'backColor',
                    false,
                    color
                );

            } catch (e) {}

        }


        document.getElementById(
            'highlightColorLine'
        ).style.background = color;

        saveSelection();

        editor.focus();

    };


    /* -------------------------------------------------------
       Insert Link
    ------------------------------------------------------- */

    window.insertLink = function () {

        restoreSelection();

        const url = prompt(
            'Enter URL:',
            'https://'
        );

        if (!url) {
            return;
        }

        document.execCommand(
            'createLink',
            false,
            url
        );

        saveSelection();

        editor.focus();

    };


    /* -------------------------------------------------------
       Horizontal Line
    ------------------------------------------------------- */

    window.insertHorizontalLine = function () {

        restoreSelection();

        document.execCommand(
            'insertHorizontalRule',
            false,
            null
        );

        saveSelection();

        editor.focus();

    };


    /* -------------------------------------------------------
       Clear Formatting
    ------------------------------------------------------- */

    window.clearFormatting = function () {

        restoreSelection();

        document.execCommand(
            'removeFormat',
            false,
            null
        );

        document.execCommand(
            'unlink',
            false,
            null
        );

        saveSelection();

        editor.focus();

    };


    /* -------------------------------------------------------
       Word Counter
    ------------------------------------------------------- */

    function updateCounter() {

        const text = editor.innerText
            .replace(/\s+/g, ' ')
            .trim();

        if (!text) {

            counter.textContent = '0 words';

            return;

        }

        const words = text.split(' ').filter(Boolean);

        counter.textContent =
            words.length +
            (words.length === 1 ? ' word' : ' words');

    }


    editor.addEventListener(
        'input',
        updateCounter
    );


    /* -------------------------------------------------------
       Paste Handling
       Keep rich formatting where possible.
    ------------------------------------------------------- */

    editor.addEventListener(
        'paste',
        function () {

            setTimeout(function () {

                saveSelection();
                updateCounter();

            }, 50);

        }
    );


    /* -------------------------------------------------------
       Form Submit
       Save HTML into description
    ------------------------------------------------------- */

    form.addEventListener(
        'submit',
        function (e) {

            const html = editor.innerHTML.trim();

            /*
             * Empty editor check
             */

            const text = editor.innerText
                .replace(/\s+/g, '')
                .trim();

            if (!text && html === '') {

                e.preventDefault();

                alert(
                    'Please write your blog description.'
                );

                editor.focus();

                return false;

            }


            /*
             * IMPORTANT:
             * Save formatted HTML.
             */

            hiddenInput.value = html;


            /*
             * Prevent double submit
             */

            const submitButton =
                document.getElementById(
                    'submitBlogBtn'
                );

            if (submitButton) {

                submitButton.disabled = true;

                submitButton.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Saving...';

            }

        }
    );


    /*
     * Initial counter
     */

    updateCounter();

})();
    

/* =========================================================
   IMAGE PREVIEW
   ========================================================= */

function previewImg(input) {

    if (
        !input.files ||
        !input.files[0]
    ) {
        return;
    }


    const img =
        document.getElementById(
            'imgPreview'
        );

    img.src =
        URL.createObjectURL(
            input.files[0]
        );

    img.style.display = 'block';


    document.getElementById(
        'uploadLabel'
    ).textContent =
        input.files[0].name;


    document.getElementById(
        'uploadZone'
    ).style.borderColor =
        'var(--p-teal)';

}
</script>

@endsection
