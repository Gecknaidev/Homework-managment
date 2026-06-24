<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeworkPro</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; min-height: 100vh; opacity: 0; transform: translateY(12px); transition: opacity 0.4s ease, transform 0.4s ease; }
        body.loaded { opacity: 1; transform: translateY(0); }
        body.exit { opacity: 0; transform: translateY(-12px); }
        body.teacher-mode { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); }
        body.student-mode { background: linear-gradient(135deg, #0b3d2e, #1a6b4a, #0d4a30); }

        .topbar { background: rgba(255,255,255,0.06); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 14px 30px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .logo { display: flex; align-items: center; gap: 10px; }
        .logo-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .teacher-mode .logo-icon { background: #534AB7; }
        .student-mode .logo-icon { background: #0F6E56; }
        .logo-icon i { color: white; font-size: 17px; }
        .logo-text { font-size: 19px; font-weight: 700; color: white; }
        .teacher-mode .logo-text span { color: #AFA9EC; }
        .student-mode .logo-text span { color: #5DCAA5; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .role-badge { padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 6px; }
        .teacher-mode .role-badge { background: rgba(83,74,183,0.3); color: #AFA9EC; border: 1px solid rgba(83,74,183,0.4); }
        .student-mode .role-badge { background: rgba(15,110,86,0.3); color: #5DCAA5; border: 1px solid rgba(15,110,86,0.4); }
        .btn-switch { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.7); padding: 8px 16px; border-radius: 20px; font-size: 13px; border: 1px solid rgba(255,255,255,0.15); cursor: pointer; display: flex; align-items: center; gap: 6px; transition: background 0.2s; font-family: inherit; }
        .btn-switch:hover { background: rgba(255,255,255,0.15); color: white; }
        .btn-new { color: white; padding: 9px 20px; border-radius: 25px; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 7px; transition: opacity 0.2s, transform 0.2s; }
        .teacher-mode .btn-new { background: #534AB7; }
        .student-mode .btn-new { background: #0F6E56; }
        .btn-new:hover { opacity: 0.85; transform: translateY(-1px); }

        .container { max-width: 920px; margin: 0 auto; padding: 30px 20px; }
        .hero { margin-bottom: 28px; }
        .hero h2 { font-size: 28px; font-weight: 800; color: white; }
        .hero p { font-size: 14px; margin-top: 6px; color: rgba(255,255,255,0.45); }

        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px; }
        .stat { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 22px; text-align: center; cursor: pointer; transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), background 0.2s; }
        .stat:hover { transform: translateY(-6px) scale(1.03); background: rgba(255,255,255,0.1); }
        .stat:active { transform: scale(0.96); }
        .stat-num { font-size: 32px; font-weight: 800; color: white; }
        .stat-label { font-size: 12px; color: rgba(255,255,255,0.45); margin-top: 5px; }
        .stat:nth-child(1) { border-top: 3px solid rgba(255,255,255,0.3); }
        .teacher-mode .stat:nth-child(2) { border-top: 3px solid #534AB7; }
        .teacher-mode .stat:nth-child(3) { border-top: 3px solid #0F6E56; }
        .student-mode .stat:nth-child(2) { border-top: 3px solid #0F6E56; }
        .student-mode .stat:nth-child(3) { border-top: 3px solid #BA7517; }

        .search { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 13px 18px; display: flex; align-items: center; gap: 10px; margin-bottom: 25px; transition: border-color 0.2s, background 0.2s; }
        .search:focus-within { border-color: rgba(255,255,255,0.3); background: rgba(255,255,255,0.09); }
        .search i { color: rgba(255,255,255,0.35); font-size: 14px; }
        .search input { flex: 1; border: none; outline: none; font-size: 14px; color: white; background: transparent; }
        .search input::placeholder { color: rgba(255,255,255,0.3); }

        .alert { padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; font-size: 14px; animation: slideDown 0.4s ease; }
        .alert-success { background: rgba(15,110,86,0.2); color: #5DCAA5; border: 1px solid rgba(15,110,86,0.3); }
        @keyframes slideDown { from { opacity:0; transform: translateY(-10px); } to { opacity:1; transform: translateY(0); } }

        .section-title { font-size: 14px; font-weight: 700; color: rgba(255,255,255,0.5); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.8px; }
        .teacher-section .section-title i { color: #AFA9EC; }
        .student-section .section-title i { color: #5DCAA5; }

        .hw-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 20px 22px; margin-bottom: 12px; transition: transform 0.2s, background 0.2s, border-color 0.2s; animation: fadeUp 0.4s ease both; }
        @keyframes fadeUp { from { opacity:0; transform: translateY(15px); } to { opacity:1; transform: translateY(0); } }
        .hw-card:hover { transform: translateY(-3px); background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.15); }
        .hw-card.teacher-card { border-left: 3px solid #534AB7; }
        .hw-card.student-card { border-left: 3px solid #0F6E56; }

        .hw-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
        .hw-title { font-size: 16px; font-weight: 600; color: white; display: flex; align-items: center; gap: 8px; }
        .teacher-card .hw-title i { color: #AFA9EC; }
        .student-card .hw-title i { color: #5DCAA5; }
        .hw-meta { display: flex; align-items: center; gap: 12px; }
        .hw-date { font-size: 12px; color: rgba(255,255,255,0.35); display: flex; align-items: center; gap: 5px; white-space: nowrap; }
        .hw-student-name { font-size: 12px; color: #5DCAA5; background: rgba(15,110,86,0.15); padding: 3px 10px; border-radius: 20px; display: flex; align-items: center; gap: 4px; border: 1px solid rgba(15,110,86,0.25); }
        .hw-desc { font-size: 13px; color: rgba(255,255,255,0.5); line-height: 1.7; margin-bottom: 14px; }
        .hw-bottom { display: flex; justify-content: space-between; align-items: center; }

        .badge { font-size: 12px; padding: 4px 12px; border-radius: 20px; font-weight: 600; display: flex; align-items: center; gap: 5px; }
        .badge-file { background: rgba(15,110,86,0.2); color: #5DCAA5; border: 1px solid rgba(15,110,86,0.3); }
        .badge-none { background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.3); border: 1px solid rgba(255,255,255,0.1); }

        .card-actions { display: flex; gap: 8px; align-items: center; }
        .btn-view { font-size: 12px; padding: 6px 14px; border-radius: 20px; background: rgba(83,74,183,0.2); color: #AFA9EC; text-decoration: none; display: flex; align-items: center; gap: 5px; border: 1px solid rgba(83,74,183,0.3); transition: background 0.2s; }
        .btn-view:hover { background: rgba(83,74,183,0.35); }
        .btn-del { font-size: 12px; padding: 6px 14px; border-radius: 20px; background: rgba(163,45,45,0.15); color: #F09595; border: 1px solid rgba(163,45,45,0.25); cursor: pointer; display: flex; align-items: center; gap: 5px; transition: background 0.2s; font-family: inherit; }
        .btn-del:hover { background: rgba(163,45,45,0.3); }

        .section-gap { margin-top: 35px; }
        .empty { text-align: center; padding: 45px 20px; color: rgba(255,255,255,0.2); }
        .empty i { font-size: 45px; display: block; margin-bottom: 12px; }
        .footer { text-align: center; padding: 30px; color: rgba(255,255,255,0.15); font-size: 13px; }

        /* LOADING */
        .loading-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 9999; opacity: 0; pointer-events: none; transition: opacity 0.3s; }
        .loading-overlay.show { opacity: 1; pointer-events: all; }
        .loading-spinner { display: flex; flex-direction: column; align-items: center; gap: 16px; }
        .spinner { width: 48px; height: 48px; border: 3px solid rgba(255,255,255,0.15); border-top-color: white; border-radius: 50%; animation: spin 0.7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .loading-spinner p { color: white; font-size: 14px; opacity: 0.7; }

        /* DIALOG */
        .dialog-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.55); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 9998; opacity: 0; pointer-events: none; transition: opacity 0.25s; }
        .dialog-overlay.show { opacity: 1; pointer-events: all; }
        .dialog { background: #1e1b3a; border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; padding: 32px; max-width: 360px; width: 90%; text-align: center; transform: scale(0.88); transition: transform 0.3s cubic-bezier(.34,1.56,.64,1); }
        .dialog-overlay.show .dialog { transform: scale(1); }
        .dialog-icon { font-size: 42px; color: #F09595; margin-bottom: 14px; }
        .dialog h3 { color: white; font-size: 18px; margin-bottom: 8px; font-weight: 700; }
        .dialog p { color: rgba(255,255,255,0.45); font-size: 14px; margin-bottom: 24px; }
        .dialog-btns { display: flex; gap: 10px; }
        .dialog-cancel { flex: 1; padding: 12px; background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; cursor: pointer; font-size: 14px; font-weight: 600; transition: background 0.2s; font-family: inherit; }
        .dialog-cancel:hover { background: rgba(255,255,255,0.13); color: white; }
        .dialog-confirm { flex: 1; padding: 12px; background: rgba(163,45,45,0.3); color: #F09595; border: 1px solid rgba(163,45,45,0.4); border-radius: 12px; cursor: pointer; font-size: 14px; font-weight: 600; transition: background 0.2s; font-family: inherit; }
        .dialog-confirm:hover { background: rgba(163,45,45,0.55); }

        /* SWITCH MODAL */
        .switch-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.55); backdrop-filter: blur(6px); display: flex; align-items: center; justify-content: center; z-index: 9998; opacity: 0; pointer-events: none; transition: opacity 0.25s; }
        .switch-overlay.show { opacity: 1; pointer-events: all; }
        .switch-dialog { background: #1e1b3a; border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; padding: 32px; max-width: 380px; width: 90%; text-align: center; transform: scale(0.88); transition: transform 0.3s cubic-bezier(.34,1.56,.64,1); }
        .switch-overlay.show .switch-dialog { transform: scale(1); }
        .switch-dialog h3 { color: white; font-size: 18px; font-weight: 700; margin-bottom: 8px; }
        .switch-dialog p { color: rgba(255,255,255,0.45); font-size: 14px; margin-bottom: 20px; }
        .switch-input { width: 100%; padding: 12px 16px; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15); border-radius: 12px; color: white; font-size: 14px; outline: none; margin-bottom: 16px; font-family: inherit; }
        .switch-input::placeholder { color: rgba(255,255,255,0.3); }
        .switch-input:focus { border-color: #0F6E56; }
        .switch-btns { display: flex; gap: 10px; }
        .switch-cancel { flex: 1; padding: 12px; background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; cursor: pointer; font-size: 14px; font-weight: 600; font-family: inherit; }
        .switch-confirm { flex: 1; padding: 12px; background: #0F6E56; color: white; border: none; border-radius: 12px; cursor: pointer; font-size: 14px; font-weight: 600; font-family: inherit; }
    </style>
</head>
<body class="{{ $role === 'teacher' ? 'teacher-mode' : 'student-mode' }}">

{{-- LOADING --}}
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner">
        <div class="spinner"></div>
        <p id="loadingText">Loading...</p>
    </div>
</div>

{{-- DELETE DIALOG --}}
<div class="dialog-overlay" id="deleteDialog">
    <div class="dialog">
        <div class="dialog-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete Homework?</h3>
        <p>This action cannot be undone. Are you sure?</p>
        <div class="dialog-btns">
            <button class="dialog-cancel" id="dialogCancel">Cancel</button>
            <button class="dialog-confirm" id="dialogConfirm">Yes, Delete</button>
        </div>
    </div>
</div>

{{-- SWITCH TO STUDENT DIALOG --}}
<div class="switch-overlay" id="switchDialog">
    <div class="switch-dialog">
        <h3>🎓 Enter Your Name</h3>
        <p>Please enter your name to continue as Student</p>
        <input type="text" class="switch-input" id="studentNameInput" placeholder="Your name...">
        <div class="switch-btns">
            <button class="switch-cancel" id="switchCancel">Cancel</button>
            <button class="switch-confirm" id="switchConfirm">Continue</button>
        </div>
    </div>
</div>

<nav class="topbar">
    <div class="logo">
        <div class="logo-icon">
            <i class="fas fa-{{ $role === 'teacher' ? 'graduation-cap' : 'book-open' }}"></i>
        </div>
        <span class="logo-text">Homework<span>Pro</span></span>
    </div>
    <div class="topbar-right">
        <span class="role-badge">
            <i class="fas fa-{{ $role === 'teacher' ? 'chalkboard-teacher' : 'user-graduate' }}"></i>
            {{ $role === 'student' && $studentName ? $studentName : ucfirst($role) }}
        </span>
        <form action="/select-role" method="POST" id="switchForm">
            @csrf
            <input type="hidden" name="role" value="{{ $role === 'teacher' ? 'student' : 'teacher' }}">
            <input type="hidden" name="student_name" id="studentNameHidden" value="">
            <button type="button" class="btn-switch" onclick="handleSwitch()">
                <i class="fas fa-sync-alt"></i>
                Switch to {{ $role === 'teacher' ? 'Student' : 'Teacher' }}
            </button>
        </form>
        <a href="/homeworks/create" class="btn-new animated-link">
            <i class="fas fa-plus"></i>
            {{ $role === 'teacher' ? 'Assign Homework' : 'Submit Homework' }}
        </a>
    </div>
</nav>

<div class="container">

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="hero">
        <h2>{{ $role === 'teacher' ? '👨‍🏫 Teacher Dashboard' : '🎓 ' . ($studentName ?: 'Student') . "'s Dashboard" }}</h2>
        <p>{{ $role === 'teacher' ? 'Assign and manage homework for your students' : 'View teacher assignments and your submitted homework' }}</p>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="stat-num">{{ $teacherHomeworks->count() + $studentHomeworks->count() }}</div>
            <div class="stat-label">Total</div>
        </div>
        <div class="stat">
            <div class="stat-num">{{ $teacherHomeworks->count() }}</div>
            <div class="stat-label">By Teacher</div>
        </div>
        <div class="stat">
            <div class="stat-num">{{ $studentHomeworks->count() }}</div>
            <div class="stat-label">{{ $role === 'teacher' ? 'By Students' : 'My Submissions' }}</div>
        </div>
    </div>

    <div class="search">
        <i class="fas fa-search"></i>
        <input type="text" id="search" placeholder="Search homeworks..." onkeyup="searchHomework()">
    </div>

    {{-- TEACHER SECTION --}}
    <div class="teacher-section">
        <div class="section-title">
            <i class="fas fa-chalkboard-teacher"></i> Assigned by Teacher
        </div>
        @forelse ($teacherHomeworks as $hw)
            <div class="hw-card teacher-card" style="animation-delay: {{ $loop->index * 0.07 }}s">
                <div class="hw-top">
                    <div class="hw-title"><i class="fas fa-book"></i> {{ $hw->title }}</div>
                    <div class="hw-meta">
                        <div class="hw-date"><i class="fas fa-clock"></i> {{ $hw->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <p class="hw-desc">{{ $hw->description ?? 'No description provided.' }}</p>
                <div class="hw-bottom">
                    @if($hw->attachment)
                        <span class="badge badge-file"><i class="fas fa-paperclip"></i> Has Attachment</span>
                    @else
                        <span class="badge badge-none"><i class="fas fa-times"></i> No Attachment</span>
                    @endif
                    <div class="card-actions">
                        @if($hw->attachment)
                            <a href="{{ asset('storage/' . $hw->attachment) }}" target="_blank" class="btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                        @endif
                        @if($role === 'teacher')
                            <form action="/homeworks/{{ $hw->id }}" method="POST" class="delete-form" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-del" onclick="openDialog(this)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty"><i class="fas fa-inbox"></i><p>No homework assigned yet.</p></div>
        @endforelse
    </div>

    {{-- STUDENT SECTION --}}
    <div class="section-gap student-section">
        <div class="section-title">
            <i class="fas fa-user-graduate"></i>
            {{ $role === 'teacher' ? 'Submitted by Students' : 'My Submissions' }}
        </div>
        @forelse ($studentHomeworks as $hw)
            <div class="hw-card student-card" style="animation-delay: {{ $loop->index * 0.07 }}s">
                <div class="hw-top">
                    <div class="hw-title"><i class="fas fa-file-alt"></i> {{ $hw->title }}</div>
                    <div class="hw-meta">
                        @if($hw->student_name && $role === 'teacher')
                            <span class="hw-student-name"><i class="fas fa-user"></i> {{ $hw->student_name }}</span>
                        @endif
                        <div class="hw-date"><i class="fas fa-clock"></i> {{ $hw->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <p class="hw-desc">{{ $hw->description ?? 'No description provided.' }}</p>
                <div class="hw-bottom">
                    @if($hw->attachment)
                        <span class="badge badge-file"><i class="fas fa-paperclip"></i> Has Attachment</span>
                    @else
                        <span class="badge badge-none"><i class="fas fa-times"></i> No Attachment</span>
                    @endif
                    <div class="card-actions">
                        @if($hw->attachment)
                            <a href="{{ asset('storage/' . $hw->attachment) }}" target="_blank" class="btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>
                        @endif
                        <form action="/homeworks/{{ $hw->id }}" method="POST" class="delete-form" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-del" onclick="openDialog(this)">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty"><i class="fas fa-inbox"></i><p>{{ $role === 'teacher' ? 'No student submissions yet.' : 'You have not submitted any homework yet.' }}</p></div>
        @endforelse
    </div>

    <div class="footer"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.body.classList.add('loaded');
    });

    // ANIMATED LINKS
    document.querySelectorAll('.animated-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.href;
            document.body.classList.remove('loaded');
            document.body.classList.add('exit');
            setTimeout(() => window.location.href = href, 300);
        });
    });

    // SWITCH ROLE
    const currentRole = '{{ $role }}';

    function handleSwitch() {
        if (currentRole === 'teacher') {
            document.getElementById('switchDialog').classList.add('show');
            setTimeout(() => document.getElementById('studentNameInput').focus(), 300);
        } else {
            showLoading('Switching to Teacher...');
            setTimeout(() => document.getElementById('switchForm').submit(), 600);
        }
    }

    document.getElementById('switchCancel').addEventListener('click', () => {
        document.getElementById('switchDialog').classList.remove('show');
    });

    document.getElementById('switchConfirm').addEventListener('click', () => {
        const name = document.getElementById('studentNameInput').value.trim();
        if (!name) {
            document.getElementById('studentNameInput').style.borderColor = '#F09595';
            return;
        }
        document.getElementById('studentNameHidden').value = name;
        document.getElementById('switchDialog').classList.remove('show');
        showLoading('Switching to Student...');
        setTimeout(() => document.getElementById('switchForm').submit(), 600);
    });

    document.getElementById('studentNameInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') document.getElementById('switchConfirm').click();
    });

    // LOADING
    function showLoading(text) {
        document.getElementById('loadingText').textContent = text;
        document.getElementById('loadingOverlay').classList.add('show');
    }

    // DELETE DIALOG
    let pendingForm = null;

function openDialog(btn) {
    pendingForm = btn.closest('form'); // FIXED
    document.getElementById('deleteDialog').classList.add('show');
}

document.getElementById('dialogConfirm').addEventListener('click', function() {
    if (pendingForm) {
        const card = pendingForm.closest('.hw-card'); // get UI card
        pendingForm.submit(); // backend delete
        card.remove(); // remove from UI instantly
    }
});

    function closeDialog() {
        document.getElementById('deleteDialog').classList.remove('show');
        pendingForm = null;
    }

    document.getElementById('dialogCancel').addEventListener('click', closeDialog);

document.getElementById('dialogConfirm').addEventListener('click', function() {
    if (pendingForm) {
        closeDialog();
        pendingForm.submit();
    }
});

    document.getElementById('deleteDialog').addEventListener('click', function(e) {
        if (e.target === this) closeDialog();
    });

    // SEARCH
    function searchHomework() {
        const input = document.getElementById('search').value.toLowerCase();
        document.querySelectorAll('.hw-card').forEach(card => {
            const title = card.querySelector('.hw-title').textContent.toLowerCase();
            card.style.display = title.includes(input) ? 'block' : 'none';
        });
    }

    // STAT BOUNCE
    document.querySelectorAll('.stat').forEach(stat => {
        stat.addEventListener('click', function() {
            this.style.transform = 'scale(0.93)';
            setTimeout(() => this.style.transform = '', 200);
        });
    });
</script>
</body>
</html>