
let userFormElt = document.getElementById('userForm');
let addButton = document.getElementById('addButton');
let editButton = document.getElementById('editButton');

userFormElt.style.display = "none";

    $('.createButton').click(function() {

        document.getElementById("user_firstName").value = "";
        document.getElementById("user_lastName").value = "";
        document.getElementById("user_email").value = "";

        addButton.style.display = "block";
        editButton.style.display = "none";

        if (userFormElt.style.display === "none") {
            userFormElt.style.display = "block";
        }
        document.getElementById("userForm").children[0].action="/user/0/manager/update";
    });

    $('.listButton').click(function() {
        let td=this.parentNode.parentNode;

        let userId=td.children[0].innerText;
        let firstName=td.children[2].innerText;
        let lastName=td.children[1].innerText;
        let email=td.children[3].innerText;
        let society=td.children[4].textContent;

        document.getElementById("user_firstName").value = firstName;
        document.getElementById("user_lastName").value = lastName;
        document.getElementById("user_email").value = email;
        let optElts = document.getElementById("user_society");

        optElts.childNodes.forEach( (option) => {
            if(option.textContent == society) {
                option.selected = "selected";
                optElts.value = option.value;
            }
    });

        addButton.style.display = "none";
        editButton.style.display = "block";

        if (userFormElt.style.display === "none") {
            userFormElt.style.display = "block";
        }
        document.getElementById("userForm").children[0].action="/user/"+userId+"/manager/update";
    });