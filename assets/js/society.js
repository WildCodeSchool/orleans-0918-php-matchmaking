let societyFormElt = document.getElementById('societyForm');
let addButton = document.getElementById('addButton');
let editButton = document.getElementById('editButton');
let currentPage = location.href.split('/').pop();

societyFormElt.style.display = "none";

$('.createButton').click(function() {

    document.getElementById("society_name").value = "";

    addButton.style.display = "block";
    editButton.style.display = "none";

    if (societyFormElt.style.display === "none") {
        societyFormElt.style.display = "block";
    }
    document.getElementById("societyForm").children[0].action="/admin/society/0/update";
});

$('.listButton').click(function() {

    let societyId=this.parentNode.parentNode.parentNode.childNodes[1].innerText;
    let name=this.parentNode.parentNode.parentNode.childNodes[3].innerText;

    document.getElementById("society_name").value = name;

    addButton.style.display = "none";
    editButton.style.display = "block";

    if (societyFormElt.style.display === "none") {
        societyFormElt.style.display = "block";
    }
    document.getElementById("societyForm").children[0].action="/admin/society/"+societyId+"/update";
});