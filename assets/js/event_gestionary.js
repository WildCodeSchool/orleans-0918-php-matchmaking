require('../css/event_gestionary.scss');

let managerInputElt = document.getElementById("gestionaryInput");
let managersElts = $('.gestionary_list')[0].children;
let managerDivElt = document.getElementsByClassName("list-group-item");
let badgeListElt = document.getElementById("badge_list");
class ManagerList {

    constructor() {
        this.managers = [];
        if (document.cookies) {
            this.managers = document.cookies["manager"].split(",");
        }
    }

    addManager(name) {
        if (!this.managers.includes(name)) {
            this.managers.push(name);
        }
        drawBadges(this.managers);
    }

    removeManager(name) {
        if (this.managers.includes(name)) {
            let targetIndex = this.managers.indexOf(name);
            this.managers.splice(targetIndex, 1);
        }
        drawBadges(this.managers);
    }

    getManagers() {
        return this.managers;
    }
}

function drawManagerList(word = "") {
    for (let i = 0; i < managersElts.length; i++) {
        managersElts[i].style.display = "none";
        for (let j = 0; j < managersElts[i].children.length; j++) {
            let textContent = managersElts[i].children[j].textContent.toLowerCase();
            if (textContent.includes(word)) {
                managersElts[i].style.display = "block";
            }
        }
    }
}

//
function drawBadges(managers) {
    badgeListElt.innerHTML = "";
    managers.forEach((manager) => {

        let badgeElt = document.createElement("span");
        let deleteElt = document.createElement("button");

        deleteElt.setAttribute("class", "btn btn-link");
        badgeElt.setAttribute("class", "badge badge-primary");
        badgeElt.textContent = manager;
        deleteElt.addEventListener("click", (elt) => {
            elt.preventDefault();
            managersList.removeManager(manager);
        });
        deleteElt.innerHTML = '<i class="fas fa-times"></i>';
        badgeElt.append(deleteElt);

        badgeListElt.append(badgeElt);
    });
    setCookie();
}

//
function setCookie() {
    console.log( managersList.getManagers());
    document.cookie = "managers="+[managersList.getManagers()];
}

let managersList = new ManagerList();
drawBadges(managersList.getManagers());

// Filtrer l'affichage des managers par rapport au champ input
managerInputElt.addEventListener("input", () => {
    search = managerInputElt.value.toLocaleLowerCase();
    drawManagerList(search);
});

// Click sur un manager de la liste => ajout à la liste des managers attribués à l'event
for (let i = 0; i < managerDivElt.length; i++) {
    managerDivElt[i].addEventListener("click", () => {
        let targetName = managerDivElt[i].children[0].textContent + ' ';
        managersList.addManager(targetName);
    });
}