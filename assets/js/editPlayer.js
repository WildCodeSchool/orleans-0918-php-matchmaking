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
    document.getElementById('formEdit').childNodes[1].childNodes[1].childNodes[1].value=data[0];
    document.getElementById('formEdit').childNodes[1].childNodes[3].childNodes[1].value=data[1];
    document.getElementById('formEdit').childNodes[1].childNodes[5].childNodes[1].value=data[2];
    document.getElementById('formEdit').childNodes[1].childNodes[7].childNodes[1].value=data[3];
}