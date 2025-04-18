
    const modal = document.getElementById("modal");
    const openBtn = document.getElementById("openModalBtn");
    const closeBtn = document.querySelector(".close");
    const cancelBtn = document.getElementById("cancelBtn");
    const addBtn = document.getElementById("addBtn");
    const modulesList = document.getElementById("modules-list");

    openBtn.onclick = () => modal.style.display = "block";
    closeBtn.onclick = () => modal.style.display = "none";
    cancelBtn.onclick = () => modal.style.display = "none";

    addBtn.onclick = () => {
        const name = document.getElementById("module-name").value.trim();
        const description = document.getElementById("module-description").value.trim();

        if (name === "") {
            alert("Veuillez entrer un nom de module.");
            return;
        }

        const module = document.createElement("div");
        module.style.border = "1px solid #ddd";
        module.style.padding = "20px";
        module.style.marginTop = "5px";
        module.style.marginRight = "10%";
        module.style.width = "30%"; // Prend 1/3 de la largeur de l'écran
        module.style.display = "inline-block"; // Permet un alignement côte à côte
        module.style.boxSizing = "border-box"; // Inclut les bordures et le padding dans la largeur
        module.style.margin = "10px";
        module.style.textAlign = "center"; // Centre le contenu du module
        module.style.borderRadius = "10px"; // Ajoute des coins arrondis
        module.style.backgroundColor = "#f9f9f9"; // Ajoute une couleur de fond

        module.innerHTML = `
        <h3>${name}</h3>
        <p>${description}</p>
        <button onclick="window.location.href='observation.html?module=${encodeURIComponent(name)}'">Observations</button>
        <button onclick="this.parentElement.remove()">Supprimer</button>
    `;
        modulesList.appendChild(module);
        modal.style.display = "none";
        document.getElementById("module-name").value = "";
        document.getElementById("module-description").value = "";
    };



