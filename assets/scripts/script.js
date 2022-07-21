const keyword = document.getElementById('keyword');
const cards = document.getElementsByClassName('card');

keyword.addEventListener('input', function(e){
    let search = e.target.value;
    console.log(search);
    fetch('/tous-les-objets/' + search)
    .then(response => response.json())
    .then((data) => {
        for (let i = 0; i < cards.length; i++) {
            for (let j = 0; j < data.length; j++) {
                while (data[j] != cards[i].id) {
                    //console.log(cards[i].id)
                    cards[i].remove();
                }
            }
        }
    })
})
// to do : add search by date
const date = document.getElementById('date');