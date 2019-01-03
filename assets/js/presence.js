let checkboxes=document.getElementsByClassName('presence');
let requestHeaders = new Headers();
requestHeaders.append("X-Requested-With", "XMLHttpRequest");

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-center",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

for (let i = 0; i < checkboxes.length; i++){
    checkboxes[i].onclick = function () {
        if (checkboxes[i].checked){
            let id=this.value;
            let presence = 1;
            fetch("/manager/player/"+id+"/"+presence, { method: "POST", headers: requestHeaders })
                .then((resp) => resp.json())
                .then((data)=>setSuccess(data))
        }
        else {
            let id=this.value;
            let presence = 0;
            fetch("/manager/player/"+id+"/"+presence, { method: "POST", headers: requestHeaders })
                .then((resp) => resp.json())
                .then((data)=>setSuccess(data))
        }
    }
}
function setSuccess(message){
    toastr.info(message);
}