
function show_loading_modal(msg){
    //sleep(1000);
    $('body').loadingModal({
      position: 'auto',
      text: msg,
      color: '#fff',
      opacity: '0.7',
      backgroundColor: 'rgb(0,0,0)',
      animation: 'wave'
    });
}

function hide_loading_modal(){
    $('body').loadingModal('hide');
    // destroy the plugin
    $('body').loadingModal('destroy');
}


show_loading_modal("ေၾကးနန္းစာ ေပးပို႕ျခင္း စနစ္ စတင္ေနပါၿပီ ... ");
setTimeout(hide_loading_modal, 1000);

