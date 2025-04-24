// Mise à jour du titre avec le nom du module depuis l'URL
const params = new URLSearchParams(window.location.search);
const moduleName = params.get('module') || "Module";
const titleElement = document.getElementById("module-title");

if (titleElement) {
    titleElement.textContent = "Observations - " + moduleName;
}

// Fonction pour ajouter une observation
function addObservation() {
    const text = document.getElementById("observation-text").value.trim();

    if (text === "") {
        alert("Merci d’écrire une observation.");
        return;
    }

    const div = document.createElement("div");
    div.className = "obs-item";

    div.innerHTML = `
  <span>${text}</span>
  <button class="delete-btn" onclick="deleteObservation(this)">Supprimer</button>
`;

    document.getElementById("obs-list").appendChild(div);
    document.getElementById("observation-text").value = "";
}

// Fonction pour supprimer une observation
function deleteObservation(button) {
    button.parentElement.remove();
}

// Attache l'événement au bouton une fois le DOM chargé
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("add-observation-btn").addEventListener("click", addObservation);
});
