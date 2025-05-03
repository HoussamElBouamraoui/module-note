// Récupération des paramètres d'URL
const params = new URLSearchParams(window.location.search);
const moduleName = params.get('module') || "Module";
const moduleId = params.get('id_module');
const studentId = window.studentId || null;

// Mise à jour du titre du module
const titleElement = document.getElementById("module-title");
if (titleElement) {
    titleElement.textContent = "Observations - " + moduleName;
}

// Charger les observations
function loadObservations() {
    if (!moduleId) {
        document.getElementById("obs-list").innerHTML = "<p style='color:red;'>Erreur : id_module manquant dans l'URL.</p>";
        return;
    }
    fetch(`../script-php/notes/get_notes.php?id_module=${moduleId}`)
        .then(res => res.json())
        .then(data => {
            const obsList = document.getElementById("obs-list");
            obsList.innerHTML = "";
            if (data.success) {
                data.notes.forEach(note => {
                    obsList.appendChild(createObservationDiv(note));
                });
            }
        });
}

function createObservationDiv(note) {
    const div = document.createElement("div");
    div.className = "obs-item";
    div.innerHTML = `
        <span>${note.commentaire}</span>
        <button class="delete-btn">Supprimer</button>
        <button class="edit-btn">Modifier</button>
    `;
    div.querySelector(".delete-btn").onclick = function() {
        deleteObservation(note.id, div);
    };
    div.querySelector(".edit-btn").onclick = function() {
        editObservation(note.id, div, note.commentaire);
    };
    return div;
}

function addObservation() {
    const commentaire = document.getElementById("observation-text").value.trim();
    if (commentaire === "") {
        alert("Merci d’écrire une observation.");
        return;
    }
    if (!studentId || !moduleId) {
        alert("Impossible de retrouver l'identifiant de l'étudiant ou du module.");
        return;
    }
    fetch('../script-php/notes/add_note.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            commentaire: commentaire,
            id_student: studentId,
            id_module: moduleId
        })
    })
        .then(res => res.text()) // <-- change temporairement .json() en .text()
        .then(text => {
            console.log("Réponse brute du serveur :", text); // <-- POUR DEBUG
            // Optionnel : retransforme en .json si possible
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    document.getElementById("observation-text").value = "";
                    loadObservations();
                } else {
                    alert(data.message);
                }
            } catch (e) {
                alert("Erreur JSON: " + text);
            }
        });
}

function deleteObservation(id, div) {
    if (!confirm("Supprimer ce commentaire ?")) return;
    fetch('../script-php/notes/delete_note.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                div.remove();
            } else {
                alert(data.message);
            }
        });
}

function editObservation(id, div, currentText) {
    div.innerHTML = `
        <textarea class="edit-textarea">${currentText}</textarea>
        <button class="save-btn">Enregistrer</button>
        <button class="cancel-btn">Annuler</button>
    `;
    div.querySelector(".save-btn").onclick = function() {
        const nouveauCommentaire = div.querySelector(".edit-textarea").value.trim();
        if (nouveauCommentaire === "") {
            alert("Le commentaire ne peut pas être vide.");
            return;
        }
        fetch('../script-php/notes/edit_note.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id, commentaire: nouveauCommentaire })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    loadObservations();
                } else {
                    alert(data.message);
                }
            });
    };
    div.querySelector(".cancel-btn").onclick = loadObservations;
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("add-observation-btn").addEventListener("click", addObservation);
    loadObservations();
});