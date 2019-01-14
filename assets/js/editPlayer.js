let requestHeaders = new Headers();
requestHeaders.append("X-Requested-With", "XMLHttpRequest");

$('.editFormBtn').click(function(){
    let id=this.getAttribute("id");
    fetch("/manager/player/data/"+id, { method: "POST", headers: requestHeaders })
        .then((resp) => resp.json())
        .then((data)=>fillForm(data, id))
});

function fillForm(data, id){
    let event=window.location.pathname.split("/").pop();
    document.getElementById('formEdit').childNodes[1].action="/manager/player/"+event+"/edit/"+id;
    document.getElementById('player_name_edit').value=data[0];
    document.getElementById('player_firstname_edit').value=data[1];
    document.getElementById('player_phone_edit').value=data[2];
    document.getElementById('player_mail_edit').value=data[3];
}