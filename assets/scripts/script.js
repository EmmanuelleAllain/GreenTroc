const keyword = document.getElementById('keyword');
const searchButton = document.getElementById('search_button');
const cards = document.getElementsByClassName('card');

keyword.addEventListener('input', function(e){
    let search = e.target.value;
    fetch('/tous-les-objets/' + search)
    .then(response => response.json())
    .then((data) => {
        for (let i = 0; i < cards.length; i++) {
            cards[i].classList.add('d-none');
            //let hasFoundCard = false;
            for (let j = 0; j < data.length; j++) {
                if (data[j] == cards[i].id) {
                    cards[i].classList.remove('d-none');
                    // hasFoundCard = true;
                    // break;
                }
            }
            // if (hasFoundCard == false) {
            //     cards[i].classList.add('d-none');
            // }
        }
     })
 })

// todo : add window.location to redirect to tous/les/objets if not already (homepage block)
searchButton.addEventListener('click', function(e){
    //window.location.href = '/tous-les-objets/';
    fetch('/tous-les-objets/' + document.getElementById('keyword').value)
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