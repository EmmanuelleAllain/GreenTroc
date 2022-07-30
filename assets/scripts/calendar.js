// make sure the user cannot ask for a date before tomorrow
const today = new Date();
today.setDate(today.getDate() + 1);
const formatedToday = today.toLocaleDateString().split('/').reverse().join('-')

document.getElementById('calendar-input').setAttribute('min', formatedToday);

// make sure the user cannot ask for a date after six months from now
const sixMonthsFromNow = new Date();
sixMonthsFromNow.setMonth(sixMonthsFromNow.getMonth() + 6)
const formatedMaxDate = sixMonthsFromNow.toLocaleDateString().split('/').reverse().join('-');

document.getElementById('calendar-input').setAttribute('max', formatedMaxDate);