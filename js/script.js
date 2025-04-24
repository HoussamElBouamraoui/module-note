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


addBtn.onclick = () => {
    const name = document.getElementById("module-name").value.trim();
    const description = document.getElementById("module-description").value.trim();

    if (name === "") {
        alert("Veuillez entrer un nom de module");
        return;
    }

    const module = document.createElement("div");
    module.classList.add("module-box"); // utilise la classe CSS

    const position = modulesList.children.length % 3;

    if (position === 0) {
        module.style.order = 1; // gauche
    } else if (position === 1) {
        module.style.order = 2; // centre
    } else {
        module.style.order = 3; // droite
    }

    module.innerHTML = `
        <h3>${name}</h3>
        <p>${description}</p>
        <button onclick="window.location.href='pages/observation.html?module=${encodeURIComponent(name)}'">Observations</button>

        <button onclick="this.parentElement.remove()" >Supprimer</button>
    `;

    modulesList.appendChild(module);
    modal.style.display = "none";
    document.getElementById("module-name").value = "";
    document.getElementById("module-description").value = "";
};
