/**
 * SANAD Accessibility Suite
 * Handles color filters, font scaling, and navigation enhancements.
 */

document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const trigger = document.getElementById('accTrigger');
    const sidebar = document.getElementById('accSidebar');
    const overlay = document.getElementById('accOverlay');
    const closeBtn = document.getElementById('accClose');

    // Toggle Sidebar
    const toggleSidebar = () => {
        sidebar.classList.toggle('is-active');
        overlay.classList.toggle('is-active');
    };

    if (trigger) trigger.addEventListener('click', toggleSidebar);
    if (overlay) overlay.addEventListener('click', toggleSidebar);
    if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);

    // Initial State from LocalStorage
    const settings = JSON.parse(localStorage.getItem('sanad_acc_settings')) || {
        filter: '',
        fontSize: '',
        highContrast: false,
        bigCursor: false,
        keyboardNav: false,
        readAloud: false
    };

    // Text to Speech logic
    let synth = window.speechSynthesis;
    let speaking = false;

    const stopSpeaking = () => {
        if (synth) {
            synth.cancel();
            speaking = false;
        }
    };

    const speakText = (text) => {
        if (!settings.readAloud || !text) return;
        stopSpeaking();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = document.documentElement.lang || 'fr-FR';
        utterance.onend = () => { speaking = false; };
        synth.speak(utterance);
        speaking = true;
    };

    // Hover detection for reading
    document.addEventListener('mouseover', (e) => {
        if (!settings.readAloud) return;

        const target = e.target;
        // Only read specific text elements to avoid noise
        if (['H1', 'H2', 'H3', 'P', 'A', 'BUTTON', 'SPAN', 'LABEL'].includes(target.tagName)) {
            const textContent = target.innerText || target.getAttribute('aria-label') || target.getAttribute('alt');
            if (textContent && textContent.trim().length > 0) {
                // Throttle/debounce to avoid machine gun speech
                if (target._readTimeout) clearTimeout(target._readTimeout);
                target._readTimeout = setTimeout(() => {
                    speakText(textContent);
                }, 400);
            }
        }
    });

    // Apply saved settings
    const applySettings = () => {
        // Reset dynamic classes
        body.classList.remove('acc-protanopia', 'acc-deuteranopia', 'acc-tritanopia', 'acc-grayscale', 'acc-high-contrast', 'font-size-md', 'font-size-lg', 'font-size-xl', 'acc-big-cursor', 'acc-keyboard-nav');

        if (settings.filter) body.classList.add(settings.filter);
        if (settings.fontSize) body.classList.add(settings.fontSize);
        if (settings.highContrast) body.classList.add('acc-high-contrast');
        if (settings.bigCursor) body.classList.add('acc-big-cursor');
        if (settings.keyboardNav) body.classList.add('acc-keyboard-nav');

        if (!settings.readAloud) stopSpeaking();

        // Update UI buttons
        document.querySelectorAll('.acc-btn').forEach(btn => {
            btn.classList.remove('is-active');
            const type = btn.dataset.accType;
            const value = btn.dataset.accValue;

            if (type === 'filter' && settings.filter === value) btn.classList.add('is-active');
            if (type === 'fontSize' && settings.fontSize === value) btn.classList.add('is-active');
            if (type === 'highContrast' && settings.highContrast) btn.classList.add('is-active');
            if (type === 'bigCursor' && settings.bigCursor) btn.classList.add('is-active');
            if (type === 'keyboardNav' && settings.keyboardNav) btn.classList.add('is-active');
            if (type === 'readAloud' && settings.readAloud) btn.classList.add('is-active');
        });

        localStorage.setItem('sanad_acc_settings', JSON.stringify(settings));
    };

    // Button Click Handlers
    document.querySelectorAll('.acc-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.dataset.accType;
            const value = btn.dataset.accValue;

            if (type === 'filter') {
                settings.filter = settings.filter === value ? '' : value;
            } else if (type === 'fontSize') {
                settings.fontSize = settings.fontSize === value ? '' : value;
            } else if (type === 'highContrast') {
                settings.highContrast = !settings.highContrast;
            } else if (type === 'bigCursor') {
                settings.bigCursor = !settings.bigCursor;
            } else if (type === 'keyboardNav') {
                settings.keyboardNav = !settings.keyboardNav;
            } else if (type === 'readAloud') {
                settings.readAloud = !settings.readAloud;
                if (settings.readAloud) {
                    speakText("Lecteur d'écran activé");
                }
            }

            applySettings();
        });
    });

    // Reset Button
    const resetBtn = document.getElementById('accReset');
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            settings.filter = '';
            settings.fontSize = '';
            settings.highContrast = false;
            settings.bigCursor = false;
            settings.keyboardNav = false;
            settings.readAloud = false;
            stopSpeaking();
            applySettings();
        });
    }

    applySettings();
});
