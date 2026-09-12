<!-- Header Partial -->
<header class="profileH">
    <h1 class="homeTracH">Trackie</h1>
    <a href="../profile/profileH.php" title="Profile">
        <div class="profileLogoH">
            <i class="fa-regular fa-user"></i>
            <p>Profile</p>
        </div>
    </a>
</header>
<script>
    window.addEventListener('load', function() {
        var isMobile = /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent) || 
                       (navigator.userAgent.includes('Macintosh') && navigator.maxTouchPoints > 1) || 
                       window.innerWidth < 768;
        if (isMobile || sessionStorage.getItem('inspectAlertShown')) return;

        setTimeout(function() {
            if (sessionStorage.getItem('inspectAlertShown')) return;

            var box = document.createElement('div');
            box.id = 'devicePromptBox';
            box.setAttribute('style', 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);width:280px;height:280px;max-width:90vw;max-height:90vh;background:#ffffff;color:#333;border-radius:1.5rem;box-shadow:0 15px 45px rgba(0,0,0,0.3);border:3px solid #B29CEE;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding:1.25rem;z-index:99999;box-sizing:border-box;font-family:system-ui,-apple-system,sans-serif;');
            box.innerHTML = `
                <span id="closeDevicePrompt" style="position:absolute;top:10px;right:16px;font-size:2rem;line-height:1;cursor:pointer;color:#888;font-weight:bold;user-select:none;">&times;</span>
                <p style="margin:0 0 0.8rem 0;font-size:1.5rem;font-weight:700;color:#222;">Enter</p>
                <p style="margin:0.4rem 0;font-size:1.05rem;color:#333;">Windows: <strong style="color:#5A00A3;">Ctrl + Shift + i</strong></p>
                <p style="margin:0.4rem 0;font-size:1.05rem;color:#333;">Mac: <strong style="color:#5A00A3;">Cmd + Shift + i</strong></p>
                <p style="margin:1rem 0 0 0;font-size:1.15rem;color:#555;font-weight:500;text-transform:capitalize;">For Better Experience.</p>
            `;
            document.body.appendChild(box);

            var closeBtn = document.getElementById('closeDevicePrompt');
            if (closeBtn) {
                closeBtn.onclick = function() {
                    sessionStorage.setItem('inspectAlertShown', 'true');
                    box.remove();
                };
            }
        }, 100);
    });
</script>
