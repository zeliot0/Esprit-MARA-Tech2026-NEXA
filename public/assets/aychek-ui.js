(() => {
  const html = document.documentElement;
  const toggle = document.getElementById("themeToggle");
  const langSel = document.getElementById("langSelect");
  const burger = document.getElementById("burger");
  const mobileNav = document.getElementById("mobileNav");

  // --- THEME ---
  const savedTheme = localStorage.getItem("theme") || "light";
  html.setAttribute("data-theme", savedTheme);

  function iconFor(theme) {
    return theme === "dark"
      ? '<i class="fa-regular fa-sun"></i>'
      : '<i class="fa-regular fa-moon"></i>';
  }
  if (toggle) toggle.innerHTML = iconFor(savedTheme);

  toggle?.addEventListener("click", () => {
    const cur = html.getAttribute("data-theme") || "light";
    const next = cur === "dark" ? "light" : "dark";
    html.setAttribute("data-theme", next);
    localStorage.setItem("theme", next);
    toggle.innerHTML = iconFor(next);
  });

  // --- I18N ---
  const dict = {
    fr: {
      nav_discover: "Découvrir",
      nav_manage: "Gérer",
      nav_categories: "Catégories",
      call: "Lancer un appel",
      hero_label: "Plateforme Solidaire",
      hero_title: "Chaque don illumine un avenir.",
      hero_accent: "illumine",
      hero_desc: "Rejoignez notre communauté de donateurs et transformez la vie de ceux qui en ont le plus besoin à travers toute la Tunisie.",
      btn_explore: "Explorer les causes"
    },
    en: {
      nav_discover: "Discover",
      nav_manage: "Dashboard",
      nav_categories: "Categories",
      call: "Start a Call",
      hero_label: "Solidarity Platform",
      hero_title: "Every donation illuminates a future.",
      hero_accent: "illuminates",
      hero_desc: "Join our community of donors and transform the lives of those who need it most across Tunisia.",
      btn_explore: "Explore causes"
    },
    ar: {
      nav_discover: "اكتشف",
      nav_manage: "لوحة التحكم",
      nav_categories: "الأصناف",
      call: "أطلق نداء",
      hero_label: "منصة تضامنية",
      hero_title: "كل تبرع ينير مستقبلاً.",
      hero_accent: "ينير",
      hero_desc: "انضم إلى مجتمعنا من المتبرعين وساهم في تغيير حياة المحتاجين في جميع أنحاء تونس.",
      btn_explore: "اكتشف الحالات"
    }
  };

  function applyLang(lang) {
    const d = dict[lang] || dict.fr;
    html.setAttribute("dir", lang === "ar" ? "rtl" : "ltr");
    html.setAttribute("lang", lang);

    document.querySelectorAll("[data-i18n]").forEach(el => {
      const key = el.getAttribute("data-i18n");
      if (d[key]) {
        // Special handling for elements with children (like hero_title)
        if (key === 'hero_title' && el.querySelector('.text-gradient-teal')) {
          const span = el.querySelector('.text-gradient-teal');
          const accentText = d['hero_accent'];
          el.innerHTML = d[key].replace(accentText, `<span class="text-gradient-teal">${accentText}</span>`);
        } else {
          el.textContent = d[key];
        }
      }
    });

    localStorage.setItem("lang", lang);
    if (langSel) langSel.value = lang;
  }

  applyLang(localStorage.getItem("lang") || "fr");
  langSel?.addEventListener("change", (e) => applyLang(e.target.value));

  // --- MOBILE NAV ---
  burger?.addEventListener("click", () => {
    mobileNav?.classList.toggle("open");
  });

  // --- ANIMATIONS (Observer) ---
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible'); // We can use CSS class instead of inline styles
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll('.animate-up').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(40px)';
    el.style.transition = 'all 0.8s cubic-bezier(0.16, 1, 0.3, 1)';
    observer.observe(el);
  });
})();
