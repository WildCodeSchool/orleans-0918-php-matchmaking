
let userFormElt = document.getElementById('userForm');

userFormElt.style.display = "none";

    $('.createButton').click(function() {
        if(userFormElt.style.display === "none")
             userFormElt.style.display = "block";
        else
            userFormElt.style.display = "none";
    });