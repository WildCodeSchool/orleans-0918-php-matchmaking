let societyFormElt = document.getElementById('societyForm');
let addButton = document.getElementById('addButton');
let editButton = document.getElementById('editButton');
let currentPage = location.href.split('/').pop();

societyFormElt.style.display = "none";

$('.createButton').click(function () {

    document.getElementById("society_name").value = "";

    addButton.style.display = "block";
    editButton.style.display = "none";

    if (societyFormElt.style.display === "none") {
        societyFormElt.style.display = "block";
    }
    document.getElementById("societyForm").children[0].action= currentPage+"0"+"/update";
});

$('.listButton').click(function () {

    let div = this.parentNode.parentNode.parentNode;
    let societyId = div.childNodes[1].innerText;
    let name = div.childNodes[3].innerText;

    document.getElementById("society_name").value = name;

    addButton.style.display = "none";
    editButton.style.display = "block";

    if (societyFormElt.style.display === "none") {
        societyFormElt.style.display = "block";
    }
    document.getElementById("societyForm").children[0].action = currentPage+societyId+"/update";
});