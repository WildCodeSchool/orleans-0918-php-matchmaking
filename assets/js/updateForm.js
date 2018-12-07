
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
    });

    $('.listButton').click(function() {
        let td=this.parentNode.parentNode;

        let firstName=td.children[1].innerText;
        let lastName=td.children[0].innerText;
        let email=td.children[2].innerText;

        document.getElementById("user_firstName").value = firstName;
        document.getElementById("user_lastName").value = lastName;
        document.getElementById("user_email").value = email;

        addButton.style.display = "none";
        editButton.style.display = "block";

        if (userFormElt.style.display === "none") {
            userFormElt.style.display = "block";
        }
    });