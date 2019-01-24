'use strict';

document.addEventListener('DOMContentLoaded', () => {
    let tridotButton = document.querySelector('.tridots > span');
    tridotButton.addEventListener('click', (e) => {
        if (tridotButton.parentElement.classList.contains('open')) {
            tridotButton.parentElement.classList.remove('open')
        } else {
            tridotButton.parentElement.classList.add('open')
        }
    })
})