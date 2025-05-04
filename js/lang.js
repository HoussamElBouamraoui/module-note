// Récupère la langue depuis le localStorage ou retourne 'fr' par défaut
function getLang() {
    return localStorage.getItem('lang') || 'fr';
}

// Gère le changement de langue via le sélecteur
document.addEventListener('DOMContentLoaded', function() {
    const selector = document.getElementById('lang-select');
    if (selector) {
        selector.value = getLang();
        selector.addEventListener('change', function() {
            localStorage.setItem('lang', this.value);
            location.reload();
        });
    }
});

// Charge le fichier de traduction JSON selon la langue courante
async function loadTranslations() {
    const lang = getLang();
    const base = location.pathname.includes('/pages/') ? '../' : '';
    const response = await fetch(base + 'langue/' + lang + '.json');
    return await response.json();
}

// Applique les traductions à tous les éléments de la page
function applyTranslations(translations) {
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (translations[key]) {
            if (el.dataset.i18nVars) {
                let text = translations[key];
                const vars = JSON.parse(el.dataset.i18nVars);
                for (const k in vars) {
                    text = text.replace(`{${k}}`, vars[k]);
                }
                el.textContent = text;
            } else {
                el.textContent = translations[key];
            }
        }
    });

    document.querySelectorAll('[data-i18n-ph]').forEach(el => {
        const key = el.getAttribute('data-i18n-ph');
        if (translations[key]) {
            el.setAttribute('placeholder', translations[key]);
        }
    });
}

// Applique la traduction à un sous-arbre DOM (utile pour les éléments dynamiques)
async function translatePage(root = document) {
    const translations = await loadTranslations();
    root.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (translations[key]) {
            if (el.dataset.i18nVars) {
                let text = translations[key];
                const vars = JSON.parse(el.dataset.i18nVars);
                for (const k in vars) {
                    text = text.replace(`{${k}}`, vars[k]);
                }
                el.textContent = text;
            } else {
                el.textContent = translations[key];
            }
        }
    });
    root.querySelectorAll('[data-i18n-ph]').forEach(el => {
        const key = el.getAttribute('data-i18n-ph');
        if (translations[key]) {
            el.setAttribute('placeholder', translations[key]);
        }
    });
}
window.translatePage = translatePage;

// Applique la traduction au chargement de la page et ajuste la direction (rtl pour arabe)
document.addEventListener('DOMContentLoaded', async function() {
    const translations = await loadTranslations();
    applyTranslations(translations);

    if (getLang() === "ar") {
        document.body.dir = "rtl";
    } else {
        document.body.dir = "ltr";
    }
});