// make sure the user cannot ask for a date before tomorrow
const today = new Date();
today.setDate(today.getDate() + 1);
<<<<<<< HEAD
const formatedToday = today.toLocaleDateString().split('/').reverse().join('-')

document.getElementById('calendar-input').setAttribute('min', formatedToday);

// make sure the user cannot ask for a date after six months from now
const sixMonthsFromNow = new Date();
sixMonthsFromNow.setMonth(sixMonthsFromNow.getMonth() + 6)
const formatedMaxDate = sixMonthsFromNow.toLocaleDateString().split('/').reverse().join('-');

document.getElementById('calendar-input').setAttribute('max', formatedMaxDate);
=======
//const formatedToday = today.toLocaleDateString().split('/').reverse().join('-')

//document.getElementById('calendar-input').setAttribute('min', formatedToday);

// make sure the user cannot ask for a date after six months from now
const sixMonthsFromNow = new Date();
sixMonthsFromNow.setMonth(sixMonthsFromNow.getMonth() + 6);
//const formatedMaxDate = sixMonthsFromNow.toLocaleDateString().split('/').reverse().join('-');

//document.getElementById('calendar-input').setAttribute('max', formatedMaxDate);

const id = document.getElementById('itemId').innerHTML;
fetch('/disableDates/' + id)
    .then(function(response){
        return response.json()
    })
    .then(function(data) {
        for (const object of data) {
            console.log(object.date)
        }
        console.log(data);
    })

import datepicker from 'js-datepicker'

const datepickerInput = datepicker('#calendar-input', 
{startDay: 1,
customDays: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
customMonths: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
position: 'br',
formatter: (input, date, instance) => {
    const value = date.toLocaleDateString()
    input.value = value},
overlayPlaceholder: 'Entrer une année',
minDate: today,
maxDate: sixMonthsFromNow,
disableMobile: true,
disabledDates: [
    new Date(2022, 6, 30) // format : month is the second parameter and start to 0
],
});
>>>>>>> 84c385fa9effdee46be8ce47325cf3482224029f
