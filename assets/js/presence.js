let checkboxes=document.getElementsByClassName('presence');
for (let i = 0; i < checkboxes.length; i++){
    checkboxes[i].onclick = function () {
        if (checkboxes[i].checked){
            let id=this.value;
            let presence = 1;
            fetch("/manager/player/"+id+"/"+presence)
                .then((resp) => resp.json())
                .then((data)=>setSuccess(data))
        }
        else {
            let id=this.value;
            let presence = 0;
            fetch("/manager/player/"+id+"/"+presence)
                .then((resp) => resp.json())
                .then((data)=>setSuccess(data))
        }
    }
}
function setSuccess(message){
    alert(message);
}