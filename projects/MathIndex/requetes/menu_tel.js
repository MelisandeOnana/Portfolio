function AfficheMenu(){
    let area_to_show = document.getElementsByClassName("barre-navigation")[0];
    area_to_show.classList.add('show');
    area_to_show.classList.remove('hidden');

}
function CachecheMenu(){
    let area_to_show = document.getElementsByClassName("barre-navigation")[0];
    area_to_show.classList.remove('show');
    area_to_show.classList.add('hidden');
}

