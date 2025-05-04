const modal = document.getElementById("modal");
const openBtn = document.getElementById("openModalBtn");
const closeBtn = document.querySelector(".close");
const cancelBtn = document.getElementById("cancelBtn");
const addBtn = document.getElementById("addBtn");
const modulesList = document.getElementById("modules-list");

openBtn.onclick = () => modal.style.display = "block";
closeBtn.onclick = () => modal.style.display = "none";
cancelBtn.onclick = () => modal.style.display = "none";

if (!isLoggedIn) {
    const addBtn = document.getElementById("openModalBtn");
    if (addBtn) addBtn.disabled = true;
}

// Fonction pour charger les modules depuis la BDD et les afficher
function loadModules() {
    fetch('script-php/module/get_modules.php')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                modulesList.innerHTML = ""; // reset
                data.modules.forEach(module => {
                    modulesList.appendChild(createModuleBox(module));
                });
            }
        });
}

// Fonction pour créer le bloc HTML d'un module
function createModuleBox(module) {
    const div = document.createElement('div');
    div.classList.add('module-box');
    div.innerHTML = `
      <h3>${module.name}</h3>
      <p>${module.desciption}</p>
      <button onclick="window.location.href='pages/observation.php?id_module=${module.id}&module=${encodeURIComponent(module.name)}'">
        <span data-i18n="observations_btn"></span>
      </button>
      <button class="btn-delete"><span data-i18n="delete"></span></button>
    `;
    div.querySelector('.btn-delete').onclick = () => {
        if (confirm('Supprimer ce module ?')) {
            fetch('script-php/module/delete_module.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id: module.id})
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        div.remove();
                    } else {
                        alert(data.message || "Erreur lors de la suppression");
                    }
                });
        }
    };
    if (typeof translatePage === "function") translatePage(div);
    return div;
}

// Charger les modules au chargement de la page
window.onload = loadModules;

// Ajout de module avec synchronisation BDD
addBtn.onclick = () => {
    const name = document.getElementById("module-name").value.trim();
    const description = document.getElementById("module-description").value.trim();

    if (name === "") {
        alert("Veuillez entrer un nom de module");
        return;
    }

    fetch('script-php/module/add_module.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({name, description})
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                modal.style.display = "none";
                document.getElementById("module-name").value = "";
                document.getElementById("module-description").value = "";
                // Recharge la liste depuis la BDD pour avoir l'ID du module
                loadModules();
            } else {
                alert(data.message);
            }
        });
};