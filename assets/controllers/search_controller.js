import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
// export default class extends Controller {
//     static targets = ['keyword', 'cards'];

//     search() {
//         fetch('/tous-les-objets/' + this.keywordTarget)
//     .then(response => response.json())
//     .then((data) => {
//         for (let i = 0; i < this.cardsTarget.length; i++) {
//             this.cardsTarget[i].classList.add('d-none');
//             for (let j = 0; j < data.length; j++) {
//                 if (data[j] == this.cardsTarget[i].id) {
//                     this.cardsTarget[i].classList.remove('d-none');
//                 }
//             } 
//         }
//      })
//     }
// }
