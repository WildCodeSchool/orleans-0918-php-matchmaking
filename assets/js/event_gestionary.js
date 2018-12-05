require('../css/event_gestionary.scss');

let gestionaryInputElt = document.getElementById("gestionaryInput");
let managersElts = $('.gestionary_list')[0].children;
let selectedManagers =  [];
let managerDivElt = document.getElementsByClassName("list-group-item");
let badgesElt = document.getElementsByClassName("badge");
let badgeListElt = document.getElementById("badge_list");
let cookies = document.cookie.split(";");

// récupérer les managers affiliés à l'événement
if(cookies.includes("toto")) {

}

// 
gestionaryInputElt.addEventListener("input", () => {    
    search = gestionaryInputElt.value.toLocaleLowerCase();
    drawManagerList(search);
});

//
for(let i = 0; i<managerDivElt.length; i++) {
    managerDivElt[i].addEventListener("click", () => {
        let targetName = managerDivElt[i].children[2].outerText +
        ' ' + managerDivElt[i].children[1].outerText;
        addManager(targetName);
    });
}

function drawManagerList(word = "") {
    for(let i=0; i<managersElts.length; i++) {
        
        managersElts[i].style.display = "none";
        
        for(let j = 0; j<managersElts[i].children.length; j++) { 
            let textContent = managersElts[i].children[j].textContent.toLowerCase();
            if(textContent.includes(word)) {
                managersElts[i].style.display = "block";
            }
        }
    }
}

function drawBadges(managers) {
    badgeListElt.innerHTML = "";
    managers.forEach((manager) => {
        
        let badgeElt = document.createElement("span");
        let deleteElt = document.createElement("button");
        
        deleteElt.setAttribute("class", "btn btn-link");
        badgeElt.setAttribute("class", "badge badge-primary");
        badgeElt.textContent = manager;
        deleteElt.innerHTML = '<i class="fas fa-times"></i>';
        badgeElt.append(deleteElt);
        
        badgeListElt.append(badgeElt);

        for(let i=0;i<badgesElt.length;i++) {
            badgesElt[i].lastChild.addEventListener("click", () => {
                removeManager(badgesElt[i].innerText);
            });
        }
    });
}

function addManager(name) {
    if(!selectedManagers.includes(name)) {
        selectedManagers.push(name);
    }
    drawBadges(selectedManagers);
    colorManagerList(name, true);
}

function removeManager(name) {
    if(selectedManagers.includes(name)) {
        let targetIndex = selectedManagers.indexOf(name);
        selectedManagers.splice(targetIndex, 1);
    }
    drawBadges(selectedManagers);
    colorManagerList(name, false);
}

function colorManagerList(name, bool) {

    for(let j=0;j<badgeListElt.children.length;j++) {
        let targetName = managersElts[j].children[2].outerText + ' ' + managersElts[j].children[1].outerText;
        if(targetName === name) {
            if(bool) {
                managersElts[j].style.backgroundColor = "green";
                console.log("oui", j);
            } 
            else {
                console.log("non", j);
                managersElts[j].style.backgroundColor = "none";
            }
        }
    }
}

// function pour renvoyé à PHP les managers affiliés à l'événement
