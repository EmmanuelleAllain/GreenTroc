const keyword = document.getElementById('keyword');
const cards = document.getElementsByClassName('card');
const cardContainer = document.getElementById('card-container');

keyword.addEventListener('input', function(e){
    let search = e.target.value;
    fetch('/tous-les-objets/' + search)
    .then(response => response.json())
    .then((data) => {
        for (let i = 0; i < cards.length; i++) {
            cards[i].classList.add('d-none');
            for (let j = 0; j < data.length; j++) {
                if (data[j] == cards[i].id) {
                    cards[i].classList.remove('d-none');
                }
            } 
        }
    })
})
// to do : add search by date
const date = document.getElementById('date');