<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $role === 'teacher' ? 'Assign Homework' : 'Submit Homework' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }
        body.loaded { opacity: 1; transform: translateY(0); }
        body.exit { opacity: 0; transform: translateY(-12px); }

        @if($role === 'teacher')
        body { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); }
        @else
        body { background: linear-gradient(135deg, #0b3d2e, #1a6b4a, #0d4a30); }
        @endif

        /* LOADING OVERLAY */
        .loading-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }
        .loading-overlay.show { opacity: 1; pointer-events: all; }
        .loading-spinner { display: flex; flex-direction: column; align-items: center; gap: 16px; }
        .spinner {
            width: 48px; height: 48px;
            border: 3px solid rgba(255,255,255,0.15);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }
        @if($role === 'teacher')
        .spinner { border-top-color: #AFA9EC; }
        @else
        .spinner { border-top-color: #5DCAA5; }
        @endif
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-spinner p { color: white; font-size: 14px; opacity: 0.7; }

        .card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 45px;
            width: 100%; max-width: 560px;
            animation: fadeUp 0.5s ease both;
        }
        @keyframes fadeUp { from { opacity:0; transform: translateY(20px); } to { opacity:1; transform: translateY(0); } }

        .header { text-align: center; margin-bottom: 35px; }
        .header .icon {
            width: 78px; height: 78px;
            border-radius: 22px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 18px;
            @if($role === 'teacher')
            background: #534AB7;
            box-shadow: 0 10px 30px rgba(83,74,183,0.4);
            @else
            background: #0F6E56;
            box-shadow: 0 10px 30px rgba(15,110,86,0.4);
            @endif
        }
        .header .icon i { color: white; font-size: 32px; }
        .header h1 { color: white; font-size: 24px; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.4); font-size: 14px; margin-top: 8px; }

        .role-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 12px;
            @if($role === 'teacher')
            background: rgba(83,74,183,0.25);
            color: #AFA9EC;
            border: 1px solid rgba(83,74,183,0.3);
            @else
            background: rgba(15,110,86,0.25);
            color: #5DCAA5;
            border: 1px solid rgba(15,110,86,0.3);
            @endif
        }

        .form-group { margin-bottom: 22px; }
        .form-group label {
            display: flex; align-items: center; gap: 7px;
            color: rgba(255,255,255,0.6);
            font-size: 12px; font-weight: 600;
            margin-bottom: 9px;
            text-transform: uppercase; letter-spacing: 0.7px;
        }
        .form-group label i {
            font-size: 11px;
            @if($role === 'teacher') color: #AFA9EC; @else color: #5DCAA5; @endif
        }
        .form-group input, .form-group textarea {
            width: 100%; padding: 14px 16px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            font-size: 14px; color: white; outline: none;
            transition: border 0.3s, background 0.3s;
            font-family: inherit;
        }
        .form-group input::placeholder, .form-group textarea::placeholder { color: rgba(255,255,255,0.22); }
        .form-group input:focus, .form-group textarea:focus {
            @if($role === 'teacher')
            border-color: #534AB7; background: rgba(83,74,183,0.08);
            @else
            border-color: #0F6E56; background: rgba(15,110,86,0.08);
            @endif
        }
        .form-group textarea { resize: vertical; min-height: 115px; }

        .file-upload {
            border: 2px dashed rgba(255,255,255,0.12);
            border-radius: 14px; padding: 32px;
            text-align: center; cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }
        .file-upload:hover {
            @if($role === 'teacher')
            border-color: #534AB7; background: rgba(83,74,183,0.07);
            @else
            border-color: #0F6E56; background: rgba(15,110,86,0.07);
            @endif
        }
        .file-upload .upload-icon {
            font-size: 38px;
            margin-bottom: 12px; display: block;
            @if($role === 'teacher') color: #AFA9EC; @else color: #5DCAA5; @endif
        }
        .file-upload p { color: rgba(255,255,255,0.4); font-size: 14px; margin-bottom: 4px; }
        .file-upload span { color: rgba(255,255,255,0.2); font-size: 12px; }
        .file-upload input { display: none; }
        .file-name {
            margin-top: 12px; font-size: 13px; font-weight: 600;
            @if($role === 'teacher') color: #AFA9EC; @else color: #5DCAA5; @endif
        }

        .error { color: #F09595; font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 5px; }

        .divider { height: 1px; background: rgba(255,255,255,0.07); margin: 8px 0 26px; }

        .btn-group { display: flex; gap: 12px; margin-top: 28px; }
        .btn-submit {
            flex: 2;
            color: white; padding: 15px; border: none;
            border-radius: 12px; font-size: 15px; cursor: pointer;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: opacity 0.2s, transform 0.2s;
            @if($role === 'teacher')
            background: #534AB7;
            box-shadow: 0 5px 20px rgba(83,74,183,0.35);
            @else
            background: #0F6E56;
            box-shadow: 0 5px 20px rgba(15,110,86,0.35);
            @endif
        }
        .btn-submit:hover { opacity: 0.88; transform: translateY(-1px); }
        .btn-submit:active { transform: scale(0.97); }
        .btn-cancel {
            flex: 1;
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.55); padding: 15px;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px; font-size: 15px;
            text-decoration: none;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .btn-cancel:hover { background: rgba(255,255,255,0.12); color: white; }
    </style>
</head>
<body>

{{-- LOADING OVERLAY --}}
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
        <div class="spinner"></div>
        <p id="loadingText">Submitting...</p>
    </div>
</div>

<div class="card">
    <div class="header">
        <div class="icon">
            <i class="fas fa-{{ $role === 'teacher' ? 'chalkboard-teacher' : 'paper-plane' }}"></i>
        </div>
        <h1>{{ $role === 'teacher' ? 'Assign Homework' : 'Submit Homework' }}</h1>
        <p>{{ $role === 'teacher' ? 'Create and assign homework for your students' : 'Upload your completed assignment' }}</p>
        <span class="role-tag">
            <i class="fas fa-{{ $role === 'teacher' ? 'chalkboard-teacher' : 'user-graduate' }}"></i>
            {{ ucfirst($role) }} Mode
        </span>
    </div>

    <div class="divider"></div>

    <form action="/homeworks" method="POST" enctype="multipart/form-data" id="hwForm">
        @csrf

        <div class="form-group">
            <label><i class="fas fa-heading"></i> Title</label>
            <input type="text" name="title"
                placeholder="{{ $role === 'teacher' ? 'Assignment title...' : 'Your homework title...' }}"
                required value="{{ old('title') }}">
            @error('title') <p class="error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label><i class="fas fa-align-left"></i> Description</label>
            <textarea name="description"
                placeholder="{{ $role === 'teacher' ? 'Describe the assignment requirements...' : 'Describe your work...' }}">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label><i class="fas fa-paperclip"></i> Attachment</label>
            <div class="file-upload" onclick="document.getElementById('fileInput').click()">
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <p>Click to upload file, image, or video</p>
                <span>Max size: 10MB</span>
                <input type="file" id="fileInput" name="attachment" onchange="showFileName(this)">
                <p class="file-name" id="fileName"></p>
            </div>
        </div>

        <div class="btn-group">
            <a href="/homeworks" class="btn-cancel" id="backBtn">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-{{ $role === 'teacher' ? 'chalkboard-teacher' : 'paper-plane' }}"></i>
                {{ $role === 'teacher' ? 'Assign Homework' : 'Submit Homework' }}
            </button>
        </div>
    </form>
</div>

<script>
    // PAGE LOAD
    window.addEventListener('load', () => {
        document.body.classList.add('loaded');
    });

    // SHOW FILE NAME
    function showFileName(input) {
        const fileName = input.files[0] ? input.files[0].name : '';
        document.getElementById('fileName').textContent = fileName ? '📎 ' + fileName : '';
    }

    // BACK BUTTON
    document.getElementById('backBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.href;
        document.body.classList.remove('loaded');
        document.body.classList.add('exit');
        showLoader('Going back...');
        setTimeout(() => window.location.href = href, 400);
    });

    // FORM SUBMIT
    document.getElementById('hwForm').addEventListener('submit', function(e) {
        e.preventDefault();
        showLoader('{{ $role === "teacher" ? "Assigning homework..." : "Submitting homework..." }}');
        setTimeout(() => this.submit(), 600);
    });

    function showLoader(text) {
        document.getElementById('loadingText').textContent = text;
        document.getElementById('loadingOverlay').classList.add('show');
    }
</script>
</body>
</html>