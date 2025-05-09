document.addEventListener('DOMContentLoaded', async function () {
    const chatMain = document.getElementById('chat-main');
    const moduleId = new URLSearchParams(window.location.search).get('id_module');

    // Génère le résumé
    const resumeBubble = appendMessage("Résumé IA en cours...", "ia");
    try {
        const resResume = await fetch('ia.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                question: "Fais un résumé detailler  des notes de ce module et qui est stylé .",
                id_module: moduleId
            })
        });
        const dataResume = await resResume.json();
        resumeBubble.innerHTML = "<b>Résumé IA :</b><br>" + formatIAResponse(dataResume.reponse || '[Pas de résumé IA]');
    } catch (err) {
        resumeBubble.innerHTML = "<i>Erreur lors de la récupération du résumé.</i>";
    }

    // Génère le QCM
    const qcmBubble = appendMessage("QCM IA en cours...", "ia");
    try {
        const resQcm = await fetch('ia.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                question: "Génère un QCM des questions de 5 à 10 à choix multiples sur les notes de ce module et je veux le qcm soit styles , avec 4 propositions par question et si il te demande les reponse tu le fournit luit les reponses ",
                id_module: moduleId
            })
        });
        const dataQcm = await resQcm.json();
        qcmBubble.innerHTML = "<b>QCM IA :</b><br>" + formatIAResponse(dataQcm.reponse || '[Pas de QCM IA]');
    } catch (err) {
        qcmBubble.innerHTML = "<i>Erreur lors de la récupération du QCM.</i>";
    }
});

document.getElementById('chat-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    const input = document.getElementById('ia-input');
    const question = input.value.trim();
    if (!question) return;
    const moduleId = new URLSearchParams(window.location.search).get('id_module');

    appendMessage(question, 'user');
    input.value = '';
    input.focus();

    const waiting = appendMessage('...', 'ia');

    try {
        const response = await fetch('ia.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ question, id_module: moduleId })
        });
        const data = await response.json();
        waiting.innerHTML = formatIAResponse(data.reponse || '[Pas de réponse IA]');
    } catch (e) {
        waiting.remove();
    }

    document.getElementById('chat-main').scrollTop = chatMain.scrollHeight;
});

document.getElementById("save-btn").addEventListener("click", async () => {
    const iaText = document.querySelector(".message-bubble.ia")?.innerText || "";
    const moduleId = new URLSearchParams(window.location.search).get('id_module');

    if (!iaText) {
        alert("Aucun résumé à sauvegarder !");
        return;
    }

    // On force le titre à vide pour laisser le PHP générer "Résumé X"
    const titre = "";

    const res = await fetch("save_resume_ia.php?id_module=" + moduleId, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ titre: titre, contenu: iaText })
    });

    const data = await res.json();
    if (data.success) {
        alert("Résumé IA sauvegardé !");
        location.reload();
    } else {
        alert("Erreur sauvegarde.");
    }
});

function appendMessage(text, sender) {
    const msg = document.createElement('div');
    msg.className = 'message-bubble ' + sender;
    msg.textContent = text;
    document.getElementById('chat-main').appendChild(msg);
    return msg;
}

function formatIAResponse(text) {
    const lines = text.split("\n").map(line => line.trim()).filter(Boolean);
    return lines.map(l => `<div>${l}</div>`).join('');
}
document.querySelectorAll('.resume-item').forEach(li => {
    li.addEventListener('click', async function() {
        // Retire la sélection précédente
        document.querySelectorAll('.resume-item.selected').forEach(el => el.classList.remove('selected'));
        // Ajoute la sélection à l'élément cliqué
        this.classList.add('selected');

        const titre = this.dataset.titre;
        const moduleId = new URLSearchParams(window.location.search).get('id_module');
        const chatMain = document.getElementById('chat-main');
        // Récupère le résumé depuis le serveur
        const res = await fetch(`get_resume.php?titre=${encodeURIComponent(titre)}&id_module=${moduleId}`);
        const data = await res.json();
        chatMain.innerHTML = '';
        if (data.resume) {
            const resumeDiv = document.createElement('div');
            resumeDiv.className = 'resume-bubble';
            resumeDiv.innerHTML = `<div class="resume-title">${data.resume.titre}</div><div>${data.resume.contenu.replace(/\n/g, '<br>')}</div>`;
            chatMain.appendChild(resumeDiv);
        } else {
            chatMain.innerHTML = "<i>Résumé introuvable.</i>";
        }
    });
});
document.getElementById('back-home')?.addEventListener('click', function() {
    window.location.href = '../index.php';
});