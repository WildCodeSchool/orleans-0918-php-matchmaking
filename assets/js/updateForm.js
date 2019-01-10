
let userFormElt = document.getElementById('userForm');
let addButton = document.getElementById('addButton');
let editButton = document.getElementById('editButton');
let currentPage = location.href.split('/').pop();

userFormElt.style.display = "none";

    $('.createButton').click(function() {

        document.getElementById("user_firstName").value = "";
        document.getElementById("user_lastName").value = "";
        document.getElementById("user_email").value = "";
        document.getElementById("formLegend").innerText = "Ajout";

        addButton.style.display = "block";
        editButton.style.display = "none";

        if (userFormElt.style.display === "none") {
            userFormElt.style.display = "block";
        }
        document.getElementById("userForm").children[0].action="/user/0/"+currentPage+"/update";
    });

    $('.listButton').click(function() {
        let td=this.parentNode.parentNode;

        let userId=td.children[0].innerText;
        let firstName=td.children[2].innerText;
        let lastName=td.children[1].innerText;
        let email=td.children[3].innerText;
        let society=td.children[5].innerText-1;

        document.getElementById("user_firstName").value = firstName;
        document.getElementById("user_lastName").value = lastName;
        document.getElementById("user_email").value = email;
        document.getElementById("user_society").selectedIndex=society;
        document.getElementById("formLegend").innerText = "Edition";

        addButton.style.display = "none";
        editButton.style.display = "block";

        if (userFormElt.style.display === "none") {
            userFormElt.style.display = "block";
        }
        document.getElementById("userForm").children[0].action="/user/"+userId+"/"+currentPage+"/update";
    });